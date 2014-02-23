<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends Admin_Controller {

    protected $_module_title;
    protected $_templates;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_module_title                  = 'текстовый блок';
        $this->_templates['index']            = 'cart/index';
        $this->_templates['add']              = 'cart/add';
        $this->_templates['edit']             = 'cart/edit';
        $this->_templates['page_select_list'] = 'page_select_list';
        $this->_templates['page_select_item'] = 'page_select_item';

        $this->template_data['module_title'] = $this->_module_title;
    }

    public function index() {
        $parent_id    = !empty($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;
        $page_list    = $this->page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $list_cart    = $this->cart_mapper->get_all_objects($parent_id, 'title asc');

        $this->template_data['page_select'] = $page_select;
        $this->template_data['list_cart']   = $list_cart;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['index'], $this->template_data);
    }

    public function add($parent_id = 0) {
        $this->scripts[]    = base_url().'js/redactor/redactor.js';
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $page_list      = $this->page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $new_cart       = new Cart_item();
        if ($this->form_validation->run() != false) {
            $new_cart->parent_id    = $this->input->post('parent_id');
            $new_cart->title        = $this->input->post('title');
            $new_cart->show_title   = $this->input->post('show_title') == 'on' ? 1 : 0;
            $new_cart->description  = $this->input->post('description');
            $new_cart->description = str_replace('<div>', '<p>', $new_cart->description);
            $new_cart->description = str_replace('</div>', '</p>', $new_cart->description);
            $new_id = $this->cart_mapper->save($new_cart);
            $parent_id = (int)$parent_id > 0 ? $parent_id = '?parent_id='.(int)$parent_id : '';
            redirect(base_url().'admin/cart/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/cartblock/');
        $this->scripts[]    = base_url().'js/redactor/redactor.js';
        $edit_cart          = $this->cart_mapper->get_object($id);
        $data = array();
        $data['id']      = $edit_cart->id;
        $data['parent_id']  = $edit_cart->parent_id;
        $data['title']    = $edit_cart->title;
        $data['show_title'] = $edit_cart->show_title;
        $data['description']= $edit_cart->description;
        $page_list        = $this->page_mapper->get_all_pages();
        $page_select        = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $edit_cart->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run() != false) {
                $edit_cart->parent_id   = $this->input->post('parent_id');
                $edit_cart->title       = $this->input->post('title');
                $edit_cart->show_title  = $this->input->post('show_title') == 'on' ? 1 : 0;
                $edit_cart->description = $this->input->post('description');
                $edit_cart->description = str_replace('<div>', '<p>', $edit_cart->description);
                $edit_cart->description = str_replace('</div>', '</p>', $edit_cart->description);
                $this->cart_mapper->save($edit_cart);
                redirect(base_url().'admin/cart/?page_id='.$parent_id.'#cartblock'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['cartblock']   = $edit_cart;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete($id, $parent_id) {
        $this->cart_mapper->delete($id);
        redirect(base_url().'admin/cart/?parent_id='.(int)$parent_id.'#cart'.$id);
    }
}