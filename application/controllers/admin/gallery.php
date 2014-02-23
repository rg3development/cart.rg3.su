<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends Admin_Controller {
    protected $_module_title;
    protected $_templates;
    protected $_gallery_mapper;
    protected $_path_to_image;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_module_title                    = 'галерея блок';
        $this->_templates['index']              = 'gallery/index';
        $this->_templates['add']                = 'gallery/add';
        $this->_templates['edit']               = 'gallery/edit';
        $this->_templates['gallery_index']      = 'gallery/image_index';
        $this->_templates['gallery_add']        = 'gallery/image_add';
        $this->_templates['gallery_edit']       = 'gallery/image_edit';
        $this->_templates['page_select_list']   = 'page_select_list';
        $this->_templates['page_select_item']   = 'page_select_item';
        $this->_page_mapper                     = new Page_mapper();
        $this->_gallery_mapper                  = new Gallery_mapper();
        $this->_path_to_image                   = $this->_gallery_mapper->get_path_to_image();

        $this->template_data['module_title']  = $this->_module_title;
        $this->template_data['path_to_image'] = $this->_path_to_image;
    }

    public function index() {
        $parent_id      = !empty($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;
        $page_list      = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $list           = $this->_gallery_mapper->get_categories($parent_id);

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
        $object             = new Gallery_category();
        if ($this->form_validation->run()) {
            $object->parent_id        = $this->input->post('parent_id');
            $object->title            = $this->input->post('title');
            $object->show_title       = $this->input->post('show_title') == 'on' ? 1 : 0;
            $object->script_type      = (int)$this->input->post('script_type');
            $object->count_per_page   = (int)$this->input->post('count_per_page') > 0 ? $this->input->post('count_per_page') : 10;

            $object->resize_width  = $this->input->post('resize_width');
            $object->resize_height = $this->input->post('resize_height');

            $this->_gallery_mapper->save($object, 'category');
            $parent_id = (int)$parent_id > 0 ? $parent_id = '?parent_id='.(int)$parent_id : '';
            redirect(base_url().'admin/gallery/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/gallery/'.$parent_id);
        $this->scripts[]= base_url().'js/tiny_mce/tiny_mce.js';
        $object            = $this->_gallery_mapper->get_category($id);
        $data = array();
        $data['id']               = $object->id;
        $data['title']            = $object->title;
        $data['show_title']       = $object->show_title;
        $data['script_type']      = $object->script_type;
        $data['count_per_page']   = $object->count_per_page;
        $data['resize_width']   = $object->resize_width;
        $data['resize_height']  = $object->resize_height;
        $page_list                = $this->_page_mapper->get_all_pages();
        $page_select              = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $object->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run()) {
                $object->parent_id          = $this->input->post('parent_id');
                $object->title             = $this->input->post('title');
                $object->show_title        = $this->input->post('show_title') == 'on' ? 1 : 0;
                $object->script_type       = (int)$this->input->post('script_type');
                $object->count_per_page    = $this->input->post('count_per_page');
                $object->resize_width   = $this->input->post('resize_width');
                $object->resize_height   = $this->input->post('resize_height');
                $this->_gallery_mapper->save($object, 'category');
                redirect(base_url().'admin/gallery/?page_id='.$parent_id.'#gallery'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete($id, $parent_id) {
        $this->_gallery_mapper->delete((int)$id, 'category');
        redirect(base_url().'admin/gallery/?parent_id='.(int)$parent_id.'#gallery'.$id);
    }

    public function show($parent_id) {
        $page_list      = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $list           = $this->_gallery_mapper->get_all_objects((int)$parent_id);
        $images         = array();
        if (sizeof($list) > 0) {
            foreach ($list as $item) {
                $tmp_image  = new Image_item($item->image_id);
                if ( $tmp_image->getId() )
                {
                    $images[] = $tmp_image->getFilenameThumb();
                } else {
                    $images[] = FALSE;
                }
                unset($tmp_image);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['list']        = $list;
        $this->template_data['parent_id']   = $parent_id;
        $this->template_data['images']      = $images;

        $this->load->admin_view($this->_templates['gallery_index'], $this->template_data);
    }

    public function add_image($parent_id = 0) {
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';

        $category = $this->_gallery_mapper->get_category($parent_id);

        $page_list            = $this->_page_mapper->get_all_pages();
        $page_select          = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $item                 = new Gallery_item();
        if ($this->form_validation->run() != FALSE) {
            if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                $small_image = new Image_item();
                $small_image->doUpload(GALLERY_UPLOAD_W, GALLERY_UPLOAD_H, 'image', 'gif|jpg|png|jpeg', GALLERY_UPLOAD_SIZE, 'gallery');
                $small_id = $small_image->Save();
                if ($object->script_type == 0) {
                    $small_image->createThumbnail($category->resize_width, $category->resize_height, 'gallery');
                }
                if ($object->script_type == 1) {
                    $small_image->createThumbnail($category->resize_width, $category->resize_height, 'gallery');
                }
            }
            $item->parent_id        = (int)$parent_id;
            $item->title            = $this->input->post('title');
            $item->priority         = $this->input->post('priority');
            $item->image_id         = $small_id;
            $item->link             = trim($this->input->post('link'));
            $item->description      = trim($this->input->post('description'));
            $this->_gallery_mapper->save($item, 'item');
            $parent_id = (int)$parent_id > 0 ? $parent_id = (int)$parent_id : '';
            redirect(base_url().'admin/gallery/show/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['gallery_add'], $this->template_data);
    }

    public function edit_image($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/gallery/'.$parent_id);
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';

        $category = $this->_gallery_mapper->get_category($parent_id);

        $item                   = $this->_gallery_mapper->get_object($id);
        $small_image            = new Image_item($item->image_id);
        $data = array();
        $data['id']             = $item->id;
        $data['title']          = $item->title;
        $data['description']    = $item->description;
        $data['link']           = $item->link;
        $data['priority']       = $item->priority;
        $data['path']           = $this->_path_to_image;
        $data['image']          = $small_image->getFilenameThumb();
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $item->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run() != FALSE) {
                if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                    $image            = new Image_item();
                    $image->doUpload(GALLERY_UPLOAD_W, GALLERY_UPLOAD_H, 'image', 'gif|jpg|png|jpeg', GALLERY_UPLOAD_SIZE, 'gallery');
                    $small_id = $small_image->Save();
                    if ($object->script_type == 0) {
                        $image->createThumbnail($category->resize_width, $category->resize_height, 'gallery');
                    }
                    if ($object->script_type == 1) {
                        $image->createThumbnail($category->resize_width, $category->resize_height, 'gallery');
                    }
                    $image_id        = $image->Save();
                    $item->image_id  = $image_id;
                }
                $item->parent_id        = (int)$parent_id;
                $item->title            = $this->input->post('title');
                $item->priority         = $this->input->post('priority');
                $item->link             = trim($this->input->post('link'));
                $item->description      = trim($this->input->post('description'));
                $this->_gallery_mapper->save($item, 'item');
                redirect(base_url().'admin/gallery/show/'.$parent_id.'#gallery'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['gallery_edit'], $this->template_data);
    }

    public function delete_image($id, $parent_id) {
        $this->_gallery_mapper->delete((int)$id, 'item');
        redirect(base_url().'admin/gallery/show/'.(int)$parent_id.'/#galleryblock'.(int)$id);
    }

    public function copy($id, $parent_id) {
        $object            = $this->_gallery_mapper->get_category($id);
        $data = array();
        $data['id']               = $object->id;
        $data['title']            = $object->title;
        $data['show_title']       = $object->show_title;
        $data['script_type']      = $object->script_type;
        $data['count_per_page']   = $object->count_per_page;
        $data['resize_width']   = $object->resize_width;
        $data['resize_height']   = $object->resize_height;

        $object                   = new Gallery_category();
        $object->parent_id        = 0;
        $object->title            = $data['title'].'-КОПИЯ';
        $object->show_title       = $data['show_title'];
        $object->script_type      = $data['script_type'];
        $object->count_per_page   = $data['count_per_page'];
        $object->resize_width   = $data['resize_width'];
        $object->resize_height   = $data['resize_height'];
        $this->_gallery_mapper->save($object, 'category');
        $copy_id = $this->db->insert_id();

        // копируем картинки
        $image_collection = $this->_gallery_mapper->get_all_objects($id);
        foreach ($image_collection as $image_copy) {
            $item                   = new Gallery_item();
            $item->parent_id        = $copy_id;
            $item->title            = $image_copy->title;
            $item->image_id         = $image_copy->image_id;
            $item->link             = $image_copy->link;
            $item->description      = $image_copy->description;
            $this->_gallery_mapper->save($item, 'item');
        }
        redirect(base_url().'admin/gallery');
    }

    public function prioritydown($id, $parent_id) {
        $this->_gallery_mapper->to_down($id, $parent_id);
        redirect(base_url().'admin/gallery/show/'.(int)$parent_id.'/#galleryblock'.(int)$id);
    }

    public function priorityup($id, $parent_id) {
        $this->_gallery_mapper->to_up($id, $parent_id);
        redirect(base_url().'admin/gallery/show/'.(int)$parent_id.'/#galleryblock'.(int)$id);
    }
}
