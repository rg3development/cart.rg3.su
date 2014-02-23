<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends Admin_Controller {

    protected $_module_title;
    protected $_templates;
    protected $_feedback_mapper;
    protected $_page_mapper;

    public function __construct() {
        parent::__construct();
        $this->_module_title                  = 'конструктор ФОС';
        $this->_templates['index']            = 'feedback/index';
        $this->_templates['history']          = 'feedback/history';
        $this->_templates['add']              = 'feedback/add';
        $this->_templates['edit']             = 'feedback/edit';
        $this->_templates['page_select_list'] = 'page_select_list';
        $this->_templates['page_select_item'] = 'page_select_item';
        $this->_page_mapper                   = new Page_mapper();
        $this->_feedback_mapper               = new Feedback_mapper();

        $this->template_data['module_title']  = $this->_module_title;
    }

    public function index ()
    {
        $ff_list = $this->_feedback_mapper->get_ff_list();

        $this->template_data['ff_list'] = $ff_list;

        $this->load->admin_view($this->_templates['index'], $this->template_data);
    }

    public function history ()
    {
        $history = $this->_feedback_mapper->get_history();

        $this->template_data['history'] = $history;

        $this->load->admin_view($this->_templates['history'], $this->template_data);
    }

    public function add ( $parent_id = 0 )
    {
        $page_list   = $this->_page_mapper->get_all_pages();
        $page_select = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);

        if ( ! empty($_POST) )
        {
            $this->form_validation->set_error_delimiters('', '<br />');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_message('valid_email', 'поле "%s" не является адресом электронной почты');

            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            $this->form_validation->set_rules('email_subject', '<b>тема письма</b>','trim|required');
            $this->form_validation->set_rules('email_to', '<b>получатель (email)</b>','required|valid_email');
            $this->form_validation->set_rules('email_from', '<b>отправитель (email)</b>','required|valid_email');
            $this->form_validation->set_rules('email_name', '<b>отправитель (имя)</b>','trim|required');

            if ( $this->form_validation->run() )
            {
                $form = new Feedback_form();
                $form->parent_id     = $this->input->post('parent_id');
                $form->title         = $this->input->post('title');
                $form->email_subject = $this->input->post('email_subject');
                $form->email_to      = $this->input->post('email_to');
                $form->email_from    = $this->input->post('email_from');
                $form->email_name    = $this->input->post('email_name');
                $form_id = $this->_feedback_mapper->save($form);

                $ff_title    = $this->input->post('ff_title');
                $ff_type     = $this->input->post('ff_type');
                $ff_required = $this->input->post('ff_required');
                if ( !empty($ff_type) && !empty($ff_title) )
                {
                    $selector_val   = $this->input->post('selector_val');
                    $selector_index = $this->input->post('selector_index');
                    if ( !empty($selector_val) && !empty($selector_index) && ( count($selector_index) == count($selector_val) ) )
                    {
                        $this->_feedback_mapper->ff_fields_add($form_id, $ff_title, $ff_type, $ff_required, $selector_index, $selector_val);
                    } else {
                        $this->_feedback_mapper->ff_fields_add($form_id, $ff_title, $ff_type, $ff_required);
                    }
                }
                redirect(base_url('admin/feedback'));
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit ( $id = 0, $parent_id = 0 )
    {
        if ( ! $id )
        {
            redirect(base_url('admin/feedback'));
        }
        $form = $this->_feedback_mapper->get_ff_object($id, TRUE);

        $page_list   = $this->_page_mapper->get_all_pages();
        $page_select = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $form->parent_id);

        $ff_fields_list = $this->_feedback_mapper->ff_fields_get($form->id);

        if ( ! empty($_POST) )
        {
            $this->form_validation->set_error_delimiters('', '<br />');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_message('valid_email', 'поле "%s" не является адресом электронной почты');

            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            $this->form_validation->set_rules('email_subject', '<b>тема письма</b>','trim|required');
            $this->form_validation->set_rules('email_to', '<b>получатель (email)</b>','required|valid_email');
            $this->form_validation->set_rules('email_from', '<b>отправитель (email)</b>','required|valid_email');
            $this->form_validation->set_rules('email_name', '<b>отправитель (имя)</b>','trim|required');

            if ( $this->form_validation->run() )
            {
                $form->parent_id     = $this->input->post('parent_id');
                $form->title         = $this->input->post('title');
                $form->email_subject = $this->input->post('email_subject');
                $form->email_to      = $this->input->post('email_to');
                $form->email_from    = $this->input->post('email_from');
                $form->email_name    = $this->input->post('email_name');
                $this->_feedback_mapper->save($form);

                $ff_title    = $this->input->post('ff_title');
                $ff_type     = $this->input->post('ff_type');
                $ff_required = $this->input->post('ff_required');
                if ( !empty($ff_type) && !empty($ff_title) )
                {
                    $this->_feedback_mapper->ff_fields_clear($form->id);

                    $selector_val   = $this->input->post('selector_val');
                    $selector_index = $this->input->post('selector_index');
                    if ( !empty($selector_val) && !empty($selector_index) && ( count($selector_index) == count($selector_val) ) )
                    {
                        $this->_feedback_mapper->ff_fields_add($form->id, $ff_title, $ff_type, $ff_required, $selector_index, $selector_val);
                    } else {
                        $this->_feedback_mapper->ff_fields_add($form->id, $ff_title, $ff_type, $ff_required);
                    }
                }

                redirect(base_url('admin/feedback'));
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['form']        = $form;
        $this->template_data['field_list']  = $ff_fields_list;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete ( $id = 0 )
    {
        $this->_feedback_mapper->delete($id);
        redirect(base_url('admin/feedback'));
    }

}