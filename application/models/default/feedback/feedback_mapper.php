<?php

class Feedback_mapper extends MY_Model {

    protected $_template    = 'feedback/form';
    protected $_template_ok = 'feedback/success';

    public function  __construct() {
        parent::__construct();
        $this->_ff_table   = 'feedback_form';
        $this->_ff_fields  = 'feedback_fields';
        $this->_ff_history = 'feedback_history';
        $this->load->model('feedback/feedback_form');
    }

    public function get_ff_object ( $object_id = 0, $ff_obj = FALSE )
    {
        $this->db->select('*');
        $this->db->from($this->_ff_table);
        $this->db->where('id', $object_id);
        $this->db->where('is_deleted', 0);
        $result = $this->db->get()->row();
        if ( $ff_obj )
        {
            return $this->_get_object($result);
        }
        return $result;
    }

    public function get_ff_list ( $parent_id = 0 )
    {
        $this->db->select('*');
        $this->db->from($this->_ff_table);
        if ( $parent_id )
        {
            $this->db->where('parent_id', $parent_id);
        }
        $this->db->where('is_deleted', 0);
        return $this->db->get()->result();
    }

    public function get_history ( $order_title = 'send_date', $order_by = 'DESC' )
    {
        $this->db->select('*');
        $this->db->from($this->_ff_history);
        $this->db->order_by($order_title, $order_by);
        return $this->db->get()->result();
    }

    public function save ( $object )
    {
        if ( $object instanceof Feedback_form )
        {
            $data = array (
               'parent_id'     => $object->parent_id,
               'title'         => $object->title,
               'email_subject' => $object->email_subject,
               'email_to'      => $object->email_to,
               'email_from'    => $object->email_from,
               'email_name'    => $object->email_name
            );
            if ( $object->id > 0 )
            {
                $this->db->where('id', $object->id);
                return $this->db->update($this->_ff_table, $data);
            } else {
                $this->db->insert($this->_ff_table, $data);
                return $this->db->insert_id();
            }
        }
        return FALSE;
    }

    public function delete ( $object_id )
    {
        $data = array (
            'is_deleted' => 1
        );
        $this->db->where('id', $object_id);
        return $this->db->update($this->_ff_table, $data);
    }

    protected function _get_object ( $data )
    {
        if ( $data )
        {
            $form = new Feedback_form();
            $form->id            = $data->id;
            $form->parent_id     = $data->parent_id;
            $form->title         = $data->title;
            $form->email_subject = $data->email_subject;
            $form->email_to      = $data->email_to;
            $form->email_from    = $data->email_from;
            $form->email_name    = $data->email_name;
            return $form;
        }
        return FALSE;
    }

    public function ff_fields_add ( $form_id, $ff_title, $ff_type, $ff_required, $selector_index = NULL, $selector_val = NULL )
    {
        if ( count($ff_title) == count($ff_type) )
        {
          for ( $i = 0; $i < count($ff_title); $i++ )
          {
            if ( $ff_title[$i] )
            {
              $data = array (
                 'title'   => $ff_title[$i],
                 'type'    => $ff_type[$i],
                 'form_id' => $form_id
              );
              if ( in_array($i, $ff_required) )
              {
                $data['required'] = 1;
              }

              if ( $selector_val && $selector_index )
              {
                $key = array_search($i, $selector_index);
                if ( $key !== FALSE )
                {
                    $data['selector_val'] = $selector_val[$key];
                }
              }

              $this->db->insert($this->_ff_fields, $data);
            }
          }
        }
    }

    public function ff_fields_get ( $form_id = 0 )
    {
        $this->db->select('*');
        $this->db->from($this->_ff_fields);
        $this->db->where('form_id', $form_id);
        return $this->db->get()->result();
    }

    public function ff_fields_clear ( $form_id = 0 )
    {
        $this->db->where('form_id', $form_id);
        $this->db->delete($this->_ff_fields);
    }

    public function get_page_content ( $page_id = 0 )
    {
        $result_form = '';
        $ff_list = $this->get_ff_list($page_id);
        foreach ( $ff_list as $f_key => $form )
        {
            $ff_fields_list = $this->ff_fields_get($form->id);
            foreach ( $ff_fields_list as $key => $field )
            {
                $f_name = "fname_{$form->id}_{$key}";
                $validation_rules = 'xss_clean|trim';
                if ( $field->required )
                {
                    $validation_rules .= '|required';
                }
                switch ( $field->type )
                {
                    case 1:
                        $validation_rules .= '|min_length[1]|max_length[255]';
                        break;

                    case 2:
                        $validation_rules .= '|valid_email';
                        break;

                    case 3:
                        $validation_rules .= "|min_length[1]|max_length[255]|callback_check_phone_number";
                        break;

                    case 4:
                        $validation_rules .= '|min_length[1]|max_length[1000]';
                        break;
                }
                $this->form_validation->set_rules($f_name, "<strong>{$field->title}</strong>", $validation_rules);
            }
            $this->form_validation->set_message('required', '<span>поле %s обязательно для заполнения</span><br/>');
            $this->form_validation->set_message('min_length', '<span>поле %s должно содержать больше символов</span><br/>');
            $this->form_validation->set_message('max_length', '<span>поле %s превысило максимальную длину</span><br/>');
            $this->form_validation->set_message('valid_email', '<span>поле <strong>%s</strong> имеет неверный формат</span><br/>');

            if ( ! empty($_POST) )
            {
                if ( $this->form_validation->run() )
                {
                    $form_data = array();
                    foreach ( $ff_fields_list as $key => $field )
                    {
                        $form_data[$field->title] = $this->input->post("fname_{$form->id}_{$key}");
                    }
                    $message = $this->_get_message($form_data);
                    // send mail
                    $this->_send_mail($form, $message);
                    // save history
                    $this->_save_history($form, $message);
                    return $this->load->site_view($this->_template_ok, NULL, TRUE);
                }
            }
            $template_data['fb_form']   = $form;
            $template_data['fb_fields'] = $ff_fields_list;

            $result_form = $this->load->site_view($this->_template, $template_data, TRUE);
        }
        return $result_form;
    }

    protected function _get_message ( $form_data )
    {
        $message = '';
        foreach ( $form_data as $name => $value )
        {
            if ( $value )
            {
                $message .= "{$name}: {$value} \n";
            }
        }
        return $message;
    }

    protected function _save_history ( $form, $message )
    {
        $data = array (
            'form_title'     => $form->title,
            'form_parent_id' => $form->parent_id,
            'email_subject'  => $form->email_subject,
            'email_to'       => $form->email_to,
            'email_from'     => $form->email_from,
            'email_name'     => $form->email_name,
            'message'        => $message
        );
        $this->db->insert($this->_ff_history, $data);
        return $this->db->insert_id();
    }

    protected function _send_mail ( $form, $message )
    {
        $this->email->from($form->email_from, $form->email_name);
        $this->email->to($form->email_to);
        $this->email->subject($form->email_subject);
        $this->email->message($message);
        $this->email->send();
    }

}