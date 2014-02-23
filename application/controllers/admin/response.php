<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Response extends Admin_Controller {
    protected $_module_title;
    protected $_templates;
    protected $_news_mapper;
    protected $_path_to_image;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_module_title                    = 'отзывы пользователей';
        $this->_templates['index']              = 'response/index';
        $this->_templates['add']                = 'response/add';
        $this->_templates['edit']               = 'response/edit';
        $this->_templates['response_index']     = 'response/response_index';
        $this->_templates['response_add']       = 'response/response_add';
        $this->_templates['response_edit']      = 'response/response_edit';
        $this->_templates['page_select_list']   = 'page_select_list';
        $this->_templates['page_select_item']   = 'page_select_item';
        $this->_page_mapper                     = new Page_mapper();
        $this->_response_mapper                 = new Response_mapper();
        $this->_path_to_image                   = $this->_response_mapper->get_path_to_image();

        $this->template_data['module_title']  = $this->_module_title;
        $this->template_data['path_to_image'] = $this->_path_to_image;
    }

    public function index() {
        $parent_id      = !empty($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;
        $page_list      = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $list           = $this->_response_mapper->get_categories($parent_id);

        $this->template_data['page_select'] = $page_select;
        $this->template_data['list']        = $list;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['index'], $this->template_data);
    }

    public function add($parent_id = 0) {
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $page_list          = $this->_page_mapper->get_all_pages();
        $page_select        = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $object             = new Response_category();
        if ($this->form_validation->run()) {
            $object->parent_id        = $this->input->post('parent_id');
            $object->title            = $this->input->post('title');
            $object->show_title       = $this->input->post('show_title') == 'on' ? 1 : 0;
            $object->count_per_page   = (int)$this->input->post('count_per_page') > 0 ? $this->input->post('count_per_page') : 10;
            $this->_response_mapper->save($object, 'category');
            $parent_id = (int)$parent_id > 0 ? $parent_id = '?parent_id='.(int)$parent_id : '';
            redirect(base_url().'admin/response/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/response/'.$parent_id);
        $object                   = $this->_response_mapper->get_category($id);
        $data = array();
        $data['id']               = $object->id;
        $data['title']            = $object->title;
        $data['show_title']       = $object->show_title;
        $data['count_per_page']   = $object->count_per_page;
        $page_list                = $this->_page_mapper->get_all_pages();
        $page_select              = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $object->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run()) {
                $object->parent_id        = $this->input->post('parent_id');
                $object->title            = $this->input->post('title');
                $object->show_title       = $this->input->post('show_title') == 'on' ? 1 : 0;
                $object->count_per_page   = $this->input->post('count_per_page');
                $this->_response_mapper->save($object, 'category');
                redirect(base_url().'admin/response/?page_id='.$parent_id.'#response'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function copy($id, $parent_id) {
        $data = array();
        $object                   = $this->_response_mapper->get_category($id);
        $data['id']               = $object->id;
        $data['title']            = $object->title;
        $data['show_title']       = $object->show_title;
        $data['count_per_page']   = $object->count_per_page;
        $object                   = new Response_category();
        $object->parent_id        = 0;
        $object->title            = $data['title'].'-КОПИЯ';
        $object->show_title       = $data['show_title'];
        $object->count_per_page   = $data['count_per_page'];
        $this->_response_mapper->save($object, 'category');
        $copy_id = $this->db->insert_id();
        // копируем отзывы
        $response_collection = $this->_response_mapper->get_all_objects($id);
        foreach ($response_collection as $response_copy) {
            $item                   = new Response_item();
            $item->parent_id        = $copy_id;
            $item->title            = $response_copy->title;
            $item->author           = $response_copy->author;
            $item->description      = $response_copy->description;
            $item->user_day         = $response_copy->user_day;
            $item->user_month       = $response_copy->user_month;
            $item->user_year        = $response_copy->user_year;
            $item->user_hour        = $response_copy->hour;
            $item->user_min         = $response_copy->min;
            $this->_response_mapper->save($item, 'item');
        }
        redirect(base_url().'admin/response');
    }


    public function delete($id, $parent_id) {
        $this->_response_mapper->delete((int)$id, 'category');
        redirect(base_url().'admin/response/?parent_id='.(int)$parent_id.'#response'.$id);
    }

    public function show($parent_id) {
        $page_list      = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $list           = $this->_response_mapper->get_all_objects((int)$parent_id);

        $this->template_data['page_select'] = $page_select;
        $this->template_data['list']        = $list;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['response_index'], $this->template_data);
    }

    public function add_response($parent_id = 0) {
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_message('is_natural', 'поле "%s" должно быть числовым');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $this->form_validation->set_rules('author', '<b>автор</b>','trim|required');
        $this->form_validation->set_rules('description', '<b>описание</b>','trim|required');
        $this->form_validation->set_rules('user_min', '<b>время публикации - минуты</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_hour', '<b>время публикации - час</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_day', '<b>время публикации - день</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_month', '<b>время публикации - месяц</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_year', '<b>время публикации - год</b>','trim|required|is_natural');
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckfinder.js';
        $this->css[]        = base_url().'js/plugins/ckeditor/sample.css';
        $page_list          = $this->_page_mapper->get_all_pages();
        $page_select        = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $item               = new Response_item();
        if ($this->form_validation->run() != FALSE) {
            $item->parent_id      = (int)$parent_id;
            $item->title          = $this->input->post('title');
            $item->author         = $this->input->post('author');
            $item->is_spec_link   = $this->input->post('is_spec_link') == 'on' ? 1 : 0;
            $item->link_new_window= $this->input->post('link_new_window') == 'on' ? 1 : 0;
            $item->spec_link      = $this->input->post('spec_link');
            $item->description    = $this->input->post('description');
            $item->user_day       = $this->input->post('user_day');
            $item->user_month     = $this->input->post('user_month');
            $item->user_year      = $this->input->post('user_year');
            $item->user_hour      = $this->input->post('user_hour');
            $item->user_min       = $this->input->post('user_min');
            $this->_response_mapper->save($item, 'item');
            $parent_id = (int)$parent_id > 0 ? $parent_id = (int)$parent_id : '';
            redirect(base_url().'admin/response/show/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['response_add'], $this->template_data);
    }

    public function edit_response($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/response/'.$parent_id);
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckfinder.js';
        $this->css[]        = base_url().'js/plugins/ckeditor/sample.css';
        $item               = $this->_response_mapper->get_object($id);
        $data = array();
        $data['id']             = $item->id;
        $data['title']          = $item->title;
        $data['author']         = $item->author;
        $data['is_spec_link']   = $item->is_spec_link;
        $data['spec_link']      = $item->spec_link;
        $data['link_new_window']= $item->link_new_window;
        $data['description']    = $item->description;
        $data['user_day']       = $item->user_day;
        $data['user_month']     = $item->user_month;
        $data['user_year']      = $item->user_year;
        $data['user_hour']      = $item->user_hour;
        $data['user_min']       = $item->user_min;
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $item->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_message('is_natural', 'поле "%s" должно быть числовым');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            $this->form_validation->set_rules('author', '<b>автор</b>','trim|required');
            $this->form_validation->set_rules('user_min', '<b>время публикации - минуты</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_hour', '<b>время публикации - час</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_day', '<b>время публикации - день</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_month', '<b>время публикации - месяц</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_year', '<b>время публикации - год</b>','trim|required|is_natural');
            $this->form_validation->set_rules('description', '<b>описание</b>','trim|required');
            if ($this->form_validation->run() != FALSE) {
                $item->parent_id        = (int)$parent_id;
                $item->title            = $this->input->post('title');
                $item->author           = $this->input->post('author');
                $item->description      = $this->input->post('description');
                $item->is_spec_link     = $this->input->post('is_spec_link') == 'on' ? 1 : 0;
                $item->link_new_window  = $this->input->post('link_new_window') == 'on' ? 1 : 0;
                $item->spec_link        = $this->input->post('spec_link');
                $item->user_day         = $this->input->post('user_day');
                $item->user_month       = $this->input->post('user_month');
                $item->user_year        = $this->input->post('user_year');
                $item->user_hour        = $this->input->post('user_hour');
                $item->user_min         = $this->input->post('user_min');
                $this->_response_mapper->save($item, 'item');
                redirect(base_url().'admin/response/show/'.$parent_id.'#response'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['response_edit'], $this->template_data);
    }

    public function delete_response($id, $parent_id) {
        $this->_response_mapper->delete((int)$id, 'item');
        redirect(base_url().'admin/response/show/'.(int)$parent_id.'#response'.(int)$id);
    }
}
