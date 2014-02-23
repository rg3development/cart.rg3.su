<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Map extends Admin_Controller {

    protected $_module_title;
    protected $_templates;
    protected $_page_mapper;
    protected $_panager_modules;
    protected $_page_layouts;

    public function __construct() {
        parent::__construct();

        $this->_templates['index']            = 'map/index';
        $this->_templates['add']              = 'map/add';
        $this->_templates['edit']             = 'map/edit';
        $this->_templates['map_item']         = 'map/map_item';
        $this->_templates['map_list']         = 'map/map_list';
        $this->_templates['page_select_list'] = 'page_select_list';
        $this->_templates['page_select_item'] = 'page_select_item';

        $this->_module_title    = 'карта сайта';
        $this->_page_mapper     = new Page_mapper();
        $this->_manager_modules = new Manager_modules();
        $this->_page_layouts  = $this->config->item('page_layouts');

        $this->template_data['module_title'] = $this->_module_title;
        $this->template_data['page_layouts'] = $this->_page_layouts;
    }

    public function index() {
        $map_array = $this->_page_mapper->get_all_pages();
        $map       = $this->_get_pages_tree($map_array, $this->_templates['map_list'], $this->_templates['map_item']);

        $this->template_data['map']  = $map;

        $this->load->admin_view($this->_templates['index'], $this->template_data);
    }

    public function add() {
        $this->scripts[] = '/js/admin/ui/jquery-ui-1.8.16.custom.min.js';
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'Поле "%s" незаполнено');
        $this->form_validation->set_message('_check_url_exists', 'Такой %s уже существует');
        $this->form_validation->set_rules('title', 'название','trim|required');
        $this->form_validation->set_rules('url', 'url','trim|required|callback__check_url_exists');
        $new_page            = new Page_item();
        $list_modules = $this->_manager_modules->get_list_module();
        if ( $this->form_validation->run() != FALSE ) {
            if (!empty($_FILES) && !empty($_FILES['image_bottom']) && $_FILES['image_bottom']['error'] == 0) {
                 $new_image = new Image_item();
                 $new_image->doUpload(PAGE_BOTTOM_IMAGE_MAX_WIDTH, PAGE_BOTTOM_IMAGE_MAX_HEIGHT, 'image_bottom', 'gif|jpg|png|jpeg', PAGE_BOTTOM_IMAGE_MAX_SIZE, 'page');
                 $new_id    = $new_image->Save();
                 $new_image->createThumbnail(PAGE_BOTTOM_IMAGE_THUMB_W, PAGE_BOTTOM_IMAGE_THUMB_H, 'page');
                 $new_page->image_id1 = $new_id;
                 unset($new_image);
            }
            if (!empty($_FILES) && !empty($_FILES['image_right']) && $_FILES['image_right']['error'] == 0) {
                 $new_image = new Image_item();
                 $new_image->doUpload(PAGE_RIGHT_IMAGE_MAX_WIDTH, PAGE_RIGHT_IMAGE_MAX_HEIGHT, 'image_right', 'gif|jpg|png|jpeg', PAGE_RIGHT_IMAGE_MAX_SIZE, 'page');
                 $new_id    = $new_image->Save();
                 $new_image->createThumbnail(PAGE_RIGHT_IMAGE_THUMB_W, PAGE_RIGHT_IMAGE_THUMB_H, 'page');
                 $new_page->image_id2 = $new_id;
                 unset($new_image);
            }
            $new_page->parent_id   = $this->input->post('parent_id');
            $new_page->url         = $this->input->post('url');
            $new_page->image_link  = $this->input->post('image_link');
            $new_page->priority    = $this->input->post('priority');
            $new_page->meta        = $this->input->post('meta');
            $new_page->description = $this->input->post('description');
            $new_page->keywords    = $this->input->post('keywords');
            $new_page->title       = $this->input->post('title');
            $new_page->show_title  = ($this->input->post('show_title') == 'on' ) ? 1 : 0;
            $new_page->alias       = $this->input->post('alias');
            $new_page->show_alias  = ($this->input->post('show_alias') == 'on' ) ? 1 : 0;
            $new_page->template    = $this->input->post('template');
            $new_page->show        = ($this->input->post('show') == 'on' ) ? 1 : 0;
            $new_page_id = $this->_page_mapper->save_page($new_page);
            $modules     = isset($_POST['active_form']) && sizeof($_POST['active_form']) > 0 ? $_POST['active_form'] : '';
            $this->_manager_modules->set_page_module($new_page_id, $modules);
            redirect(base_url('admin/map'));
        }
        $page_list   = $this->_page_mapper->get_all_pages();
        $page_select = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item']);

        $this->template_data['page_select']    = $page_select;
        $this->template_data['list_modules']   = $list_modules;
        $this->template_data['active_modules'] = array();

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0) {
        $this->scripts[] = '/js/admin/ui/jquery-ui-1.8.16.custom.min.js';
        $id = (int) $id;
        if ( $id == 0 ) {
            redirect(base_url('admin/map'));
        }
        $edit_page = $this->_page_mapper->get_page($id);
        $edit_image_bottom     = new Image_item($edit_page->image_id1);
        $edit_image_right      = new Image_item($edit_page->image_id2);
        $data = array();
        $data['id']            = $id;
        $data['title']         = $edit_page->title;
        $data['show_title']    = $edit_page->show_title;
        $data['alias']         = $edit_page->alias;
        $data['show_alias']    = $edit_page->show_alias;
        $data['url']           = $edit_page->url;
        $data['image_id1']     = $edit_page->image_id1;
        $data['image_id2']     = $edit_page->image_id2;
        $data['image_bottom']  = $edit_image_bottom->getFilenameThumb();
        $data['image_right']   = $edit_image_right->getFilenameThumb();
        $data['image_link']    = $edit_page->image_link;
        $data['priority']      = $edit_page->priority;
        $data['parent_id']     = $edit_page->parent_id;
        $data['meta']          = $edit_page->meta;
        $data['keywords']      = $edit_page->keywords;
        $data['description']   = $edit_page->description;
        $data['show']          = $edit_page->show;
        $data['template']      = $edit_page->template;
        $data['path_to_image'] = $this->_page_mapper->get_path_to_image();


        $page_list       = $this->_page_mapper->get_all_pages();
        $page_select     = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $edit_page->parent_id);
        $list_modules    = $this->_manager_modules->get_list_module();
        $active_modules  = $this->_manager_modules->get_page_module($edit_page->id);
        $noactive_module = $this->_check_list_modules($list_modules, $active_modules);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'Поле "%s" незаполнено');
            $this->form_validation->set_message('_check_url_exists', 'Такой %s уже существует');
            $this->form_validation->set_rules('title', 'название','trim|required');
            $this->form_validation->set_rules('url', 'url','trim|required|callback__check_url_exists');
            if ($this->form_validation->run() != FALSE) {
                $show      = !empty($_POST['show']) && $_POST['show'] == 'on' ? 1 : 0;
                $parent_id  = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0;
                $modules    = isset($_POST['active_form']) && sizeof($_POST['active_form']) > 0 ? $_POST['active_form'] : '';
                if (!empty($_FILES) && !empty($_FILES['image_bottom']) && $_FILES['image_bottom']['error'] == 0) {
                     $edit_image = new Image_item();
                     $edit_image->doUpload(PAGE_BOTTOM_IMAGE_MAX_WIDTH, PAGE_BOTTOM_IMAGE_MAX_HEIGHT, 'image_bottom', 'gif|jpg|png|jpeg', PAGE_BOTTOM_IMAGE_MAX_SIZE, 'page');
                     $edit_id = $edit_image->Save();
                     $edit_image->createThumbnail(PAGE_BOTTOM_IMAGE_THUMB_W, PAGE_BOTTOM_IMAGE_THUMB_H, 'page');
                     $edit_page->image_id1 = $edit_id;
                     unset($edit_image);
                }
                if (!empty($_FILES) && !empty($_FILES['image_right']) && $_FILES['image_right']['error'] == 0) {
                     $edit_image = new Image_item();
                     $edit_image->doUpload(PAGE_RIGHT_IMAGE_MAX_WIDTH, PAGE_RIGHT_IMAGE_MAX_HEIGHT, 'image_right', 'gif|jpg|png|jpeg', PAGE_RIGHT_IMAGE_MAX_SIZE, 'page');
                     $edit_id = $edit_image->Save();
                     $edit_image->createThumbnail(PAGE_RIGHT_IMAGE_THUMB_W, PAGE_RIGHT_IMAGE_THUMB_H, 'page');
                     $edit_page->image_id2 = $edit_id;
                     unset($edit_image);
                }
                $edit_page->parent_id   = $parent_id;
                $edit_page->priority    = $this->input->post('priority');
                $edit_page->title       = $this->input->post('title');
                $edit_page->show_title  = $this->input->post('show_title') == 'on' ? 1 : 0;
                $edit_page->alias       = $this->input->post('alias');
                $edit_page->show_alias  = $this->input->post('show_alias') == 'on' ? 1 : 0;
                $edit_page->url         = $this->input->post('url');
                $edit_page->image_link  = $this->input->post('image_link');
                $edit_page->meta        = $this->input->post('meta');
                $edit_page->keywords    = $this->input->post('keywords');
                $edit_page->description = $this->input->post('description');
                $edit_page->template    = $this->input->post('template');
                $edit_page->show        = $show;
                $this->_page_mapper->save_page($edit_page);
                $this->_manager_modules->remove_page_module($edit_page->id);
                $this->_manager_modules->set_page_module($edit_page->id, $modules);
                redirect(base_url().'admin/map/');
            }
        }
        $this->template_data['data']           = $data;
        $this->template_data['page_select']    = $page_select;
        $this->template_data['list_modules']   = $noactive_module;
        $this->template_data['active_modules'] = $active_modules;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete($id) {
        $this->_page_mapper->delete_page($id);
        redirect(base_url('admin/map'));
    }

    public function prioritydown($id) {
        $id = (int)$id;
        $this->_page_mapper->page_to_down($id);
        redirect(base_url().'admin/map/#page'.$id);
    }

    public function priorityup($id) {
        $id = (int)$id;
        $this->_page_mapper->page_to_up($id);
        redirect(base_url().'admin/map/#page'.$id);
    }

    public function editmodules($id) {
        echo 'Управление модулями на странице';
    }

    public function _check_list_modules($list_modules = array() , $active_modules = array()) {
        $res = array();
        foreach ($list_modules as $list_module) {
            $flag = false;
            foreach ($active_modules as $active_module) {
                if ($active_module['id'] == $list_module['id']) {
                    $flag = true;
                }
            }
            if (!$flag) $res[] = $list_module;
        }
        return $res;
    }

    public function _check_url_exists($url) {
        $page_id = (int)$this->uri->segment(4);
        return $this->_page_mapper->check_url_exist($page_id, $url);
    }

    public function delete_image_right($id) {
        $edit_page = $this->_page_mapper->get_page((int)$id);
        $edit_page->image_id2 = 0;
        $this->_page_mapper->save_page($edit_page);
        redirect(base_url().'admin/map/edit/'.(int)$id);
    }


    public function delete_image_bottom($id) {
        $edit_page = $this->_page_mapper->get_page((int)$id);
        $edit_page->image_id1 = 0;
        $this->_page_mapper->save_page($edit_page);
        redirect(base_url().'admin/map/edit/'.(int)$id);
    }

    // upload image for imperavi editor
    public function imeravi_upload_image() {
        $_FILES['file']['type'] = strtolower($_FILES['file']['type']);
        if ($_FILES['file']['type'] == 'image/png' || $_FILES['file']['type'] == 'image/jpg' || $_FILES['file']['type'] == 'image/gif' || $_FILES['file']['type'] == 'image/jpeg'|| $_FILES['file']['type'] == 'image/pjpeg') {
            $file = md5(date('YmdHis')).'.jpg';
            copy($_FILES['file']['tmp_name'], EDITORPATH.$file);
            $array = array('filelink' => EDITORSRC.$file);
            echo stripslashes(json_encode($array));
        }
    }

    // upload file for imperavi editor
    public function imeravi_upload_file() {
        copy($_FILES['file']['tmp_name'], EDITORPATH.$_FILES['file']['name']);
        $array = array(
            'filelink' => EDITORSRC.$_FILES['file']['name'],
            'filename' => $_FILES['file']['name']
        );
        echo stripslashes(json_encode($array));
    }
}