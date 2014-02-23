<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends Admin_Controller {
    protected $_module_title;
    protected $_templates;
    protected $_banner_mapper;
    protected $_path_to_image;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_module_title = 'баннер блок';
        $this->_templates['index']            = 'banner/index';
        $this->_templates['add']              = 'banner/add';
        $this->_templates['edit']             = 'banner/edit';
        $this->_templates['banner_index']     = 'banner/image_index';
        $this->_templates['banner_add']       = 'banner/image_add';
        $this->_templates['banner_edit']      = 'banner/image_edit';
        $this->_templates['page_select_list'] = 'page_select_list';
        $this->_templates['page_select_item'] = 'page_select_item';
        $this->_page_mapper   = new Page_mapper();
        $this->_banner_mapper = new Banner_mapper();
        $this->_path_to_image = $this->_banner_mapper->get_path_to_image();

        $this->template_data['module_title']  = $this->_module_title;
        $this->template_data['path_to_image'] = $this->_path_to_image;
    }

    public function index() {
        $parent_id    = !empty($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;
        $page_list    = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $list           = $this->_banner_mapper->get_categories($parent_id);

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
        $object             = new Banner_category();
        if ($this->form_validation->run()) {
            $object->parent_id      = $this->input->post('parent_id');
            $object->title          = $this->input->post('title');
            $object->show_title     = $this->input->post('show_title') == 'on' ? 1 : 0;
            $object->count_per_page = (int)$this->input->post('count_per_page') > 0 ? $this->input->post('count_per_page') : 10;
            $this->_banner_mapper->save($object, 'category');
            $parent_id = (int)$parent_id > 0 ? $parent_id = '?parent_id='.(int)$parent_id : '';
            redirect(base_url().'admin/banner/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/banner/'.$parent_id);
        $object         = $this->_banner_mapper->get_category($id, 'object');
        $data = array();
        $data['id']             = $object->id;
        $data['title']          = $object->title;
        $data['resize_width']   = $object->resize_width;
        $data['resize_height']  = $object->resize_height;
        $data['show_title']     = $object->show_title;
        $data['count_per_page'] = $object->count_per_page;
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $object->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run()) {
                $object->parent_id      = $this->input->post('parent_id');
                $object->title          = $this->input->post('title');
                $object->resize_width   = $this->input->post('resize_width');
                $object->resize_height  = $this->input->post('resize_height');
                $object->show_title     = $this->input->post('show_title') == 'on' ? 1 : 0;
                $object->count_per_page = $this->input->post('count_per_page');
                $this->_banner_mapper->save($object, 'category');
                redirect(base_url().'admin/banner/?page_id='.$parent_id.'#banner'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['data']        = $data;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete($id, $parent_id) {
        $this->_banner_mapper->delete((int)$id, 'category');
        redirect(base_url().'admin/banner/?parent_id='.(int)$parent_id.'#banner'.$id);
    }

    public function show($parent_id) {
        $page_list    = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $list           = $this->_banner_mapper->get_all_objects((int)$parent_id);
        $images         = array();
        if (sizeof($list) > 0) {
            foreach ($list as $item) {
                $tmp_image  = new Image_item($item->image_id);
                if ( $tmp_image->getId() )
                {
                    $images[]   = $tmp_image->getFilenameThumb();
                } else {
                    $images[]   = FALSE;
                }
                unset($tmp_image);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['list']        = $list;
        $this->template_data['images']      = $images;

        $this->load->admin_view($this->_templates['banner_index'], $this->template_data);
    }

    public function add_image($parent_id = 0) {
        $this->scripts[] = base_url('js/plugins/ckeditor/ckeditor.js');

        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $page_list          = $this->_page_mapper->get_all_pages();
        $page_select        = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $category           = $this->_banner_mapper->get_category($parent_id);
        $item               = new Banner_item();
        if ($this->form_validation->run()) {
            if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                $mini_image             = new Image_item();
                $mini_image->doUpload(BANNER_IMAGE_MAX_WIDTH, BANNER_IMAGE_MAX_HEIGHT, 'image', 'gif|jpg|png', BANNER_IMAGE_MAX_SIZE, 'banner');
                $mini_image_id          = $mini_image->Save();
                $item->image_id         = $mini_image_id;
                $mini_image->createThumbnail($category->resize_width, $category->resize_height, 'banner');
            }
            $item->parent_id        = (int)$parent_id;
            $item->title            = $this->input->post('title');
            $item->priority         = $this->input->post('priority');
            $item->link             = trim($this->input->post('link'));
            $item->link_new_window  = isset($_POST['link_new_window']) ? '1' : '0';
            $item->display          = isset($_POST['display']) ? '1' : '0';
            $item->description      = trim($this->input->post('description'));
            $this->_banner_mapper->save($item, 'item');
            $parent_id = (int)$parent_id > 0 ? $parent_id = (int)$parent_id : '';
            redirect(base_url().'admin/banner/show/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['banner_add'], $this->template_data);
    }

    public function edit_image($id = 0, $parent_id = 0, $toggle_display = false) {
        $this->scripts[] = base_url('js/plugins/ckeditor/ckeditor.js');
        $this->scripts[] = base_url('js/plugins/ckeditor/ckfinder.js');
        $this->css[]     = base_url('js/plugins/ckeditor/sample.css');

        $parent_id = (int)$parent_id;
        if ((int)$id == 0) redirect(base_url().'admin/banner/'.$parent_id);
        $category               = $this->_banner_mapper->get_category($parent_id, 'object');
        $item                   = $this->_banner_mapper->get_object($id);
        $image                  = new Image_item($item->image_id);
        $mini_image             = new Image_item($item->mini_image_id);
        $data = array();
        $data['id']             = $item->id;
        $data['title']          = $item->title;
        $data['description']    = $item->description;
        $data['link']           = $item->link;
        $data['link_new_window']= $item->link_new_window;
        $data['display']        = $item->display;
        $data['priority']       = $item->priority;
        $data['path']           = $this->_path_to_image;
        $data['image']          = $image->getFilenameThumb();
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $item->parent_id);
        if ($toggle_display && $id && $parent_id) {
            $sql = "SELECT id FROM banner_item WHERE display='1' AND parent_id = {$parent_id}";
            if (($this->db->query($sql)->num_rows >= $category->count_per_page) && (!$item->display)) {
                die('-1');
            }
            $item->display = !$item->display;
            $this->_banner_mapper->save($item);
            die(json_encode($item->display)); //ajax terminator
        }
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run() != FALSE) {
                if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                    $mini_image             = new Image_item();
                    $mini_image->doUpload(BANNER_IMAGE_MAX_WIDTH, BANNER_IMAGE_MAX_HEIGHT, 'image', 'gif|jpg|png', BANNER_IMAGE_MAX_SIZE, 'banner');
                    $mini_image_id          = $mini_image->Save();
                    $item->image_id         = $mini_image_id;
                    $mini_image->createThumbnail($category->resize_width, $category->resize_height, 'banner');
                }
                $item->parent_id        = (int)$parent_id;
                $item->title            = $this->input->post('title');
                $item->priority         = $this->input->post('priority');
                $item->link             = trim($this->input->post('link'));
                $item->link_new_window  = isset($_POST['link_new_window']) ? '1' : '0';
                $item->description      = trim($this->input->post('description'));
                $this->_banner_mapper->save($item, 'item');
                redirect(base_url().'admin/banner/show/'.$parent_id.'#banner'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['data']        = $data;

        $this->load->admin_view($this->_templates['banner_edit'], $this->template_data);
    }

    public function delete_image($id, $parent_id) {
        $this->_banner_mapper->delete((int)$id, 'item');
        redirect(base_url().'admin/banner/show/'.(int)$parent_id.'/#bannerblock'.(int)$id);
    }

    public function prioritydown($id, $parent_id) {
        $this->_banner_mapper->to_down($id, $parent_id);
        redirect(base_url().'admin/banner/show/'.(int)$parent_id.'/#bannerblock'.(int)$id);
    }

    public function priorityup($id, $parent_id) {
        $this->_banner_mapper->to_up($id, $parent_id);
        redirect(base_url().'admin/banner/show/'.(int)$parent_id.'/#bannerblock'.(int)$id);
    }
}
