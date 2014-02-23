<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends Admin_Controller {
    protected $_module_title;
    protected $_templates;
    protected $_news_mapper;
    protected $_path_to_image;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_module_title                    = 'новостной блок';
        $this->_templates['index']              = 'news/index';
        $this->_templates['add']                = 'news/add';
        $this->_templates['edit']               = 'news/edit';
        $this->_templates['news_index']         = 'news/news_index';
        $this->_templates['news_add']           = 'news/news_add';
        $this->_templates['news_edit']          = 'news/news_edit';
        $this->_templates['page_select_list']   = 'page_select_list';
        $this->_templates['page_select_item']   = 'page_select_item';
        $this->_page_mapper                     = new Page_mapper();
        $this->_news_mapper                     = new News_mapper();
        $this->_path_to_image                   = $this->_news_mapper->get_path_to_image();

        $this->template_data['module_title']  = $this->_module_title;
        $this->template_data['path_to_image'] = $this->_path_to_image;
    }

    public function index() {
        $parent_id      = !empty($_REQUEST['parent_id']) ? (int)$_REQUEST['parent_id'] : 0;
        $page_list      = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $list           = $this->_news_mapper->get_categories($parent_id);

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
        $object             = new News_category();
        if ($this->form_validation->run()) {
            $object->parent_id        = $this->input->post('parent_id');
            $object->title            = $this->input->post('title');
            $object->show_title       = $this->input->post('show_title') == 'on' ? 1 : 0;
            $object->count_per_page   = (int)$this->input->post('count_per_page') > 0 ? $this->input->post('count_per_page') : 10;
            $object->rss              = $this->input->post('rss') == 'on' ? 1 : 0;

            $object->resize_width  = $this->input->post('resize_width');
            $object->resize_height = $this->input->post('resize_height');

            $this->_news_mapper->save($object, 'category');
            $parent_id = (int)$parent_id > 0 ? $parent_id = '?parent_id='.(int)$parent_id : '';
            redirect(base_url().'admin/news/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/news/'.$parent_id);
        $object                   = $this->_news_mapper->get_category($id);
        $data = array();
        $data['id']             = $object->id;
        $data['title']          = $object->title;
        $data['show_title']     = $object->show_title;
        $data['count_per_page'] = $object->count_per_page;
        $data['rss']            = $object->rss;
        $data['resize_width']   = $object->resize_width;
        $data['resize_height']  = $object->resize_height;
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $object->parent_id);
        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            if ($this->form_validation->run()) {
                $object->parent_id        = $this->input->post('parent_id');
                $object->title            = $this->input->post('title');
                $object->show_title       = $this->input->post('show_title') == 'on' ? 1 : 0;
                $object->rss              = $this->input->post('rss') == 'on' ? 1 : 0;
                $object->count_per_page   = $this->input->post('count_per_page');
                $object->resize_width   = $this->input->post('resize_width');
                $object->resize_height   = $this->input->post('resize_height');
                $this->_news_mapper->save($object, 'category');
                redirect(base_url().'admin/news/?page_id='.$parent_id.'#news'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function copy($id, $parent_id) {
        $object                   = $this->_news_mapper->get_category($id);
        $data = array();
        $data['id']               = $object->id;
        $data['title']            = $object->title;
        $data['show_title']       = $object->show_title;
        $data['rss']              = $object->rss;
        $data['count_per_page']   = $object->count_per_page;
        $data['resize_width']   = $object->resize_width;
        $data['resize_height']   = $object->resize_height;

        $object                   = new News_category();
        $object->parent_id        = 0;
        $object->title            = $data['title'].'-КОПИЯ';
        $object->show_title       = $data['show_title'];
        $object->rss              = $data['rss'];
        $object->count_per_page   = $data['count_per_page'];
        $object->resize_width   = $data['resize_width'];
        $object->resize_height   = $data['resize_height'];
        $this->_news_mapper->save($object, 'category');
        $copy_id = $this->db->insert_id();

        // копируем новости
        $news_collection = $this->_news_mapper->get_all_objects($id);
        foreach ($news_collection as $news_copy) {
            $item                   = new News_item();
            $item->parent_id        = $copy_id;
            $item->title            = $news_copy->title;
            $item->anno             = $news_copy->anno;
            $item->description      = $news_copy->description;
            $item->user_day         = $news_copy->user_day;
            $item->user_month       = $news_copy->user_month;
            $item->user_year        = $news_copy->user_year;
            $item->user_hour        = $news_copy->hour;
            $item->user_min         = $news_copy->min;
            $item->image_id         = $news_copy->image_id;
            $item->inner_image      = $news_copy->inner_image;
            $item->inner_position   = $news_copy->inner_position;
            $this->_news_mapper->save($item, 'item');
        }
        redirect(base_url().'admin/news');
    }


    public function delete($id, $parent_id) {
        $this->_news_mapper->delete((int)$id, 'category');
        redirect(base_url().'admin/news/?parent_id='.(int)$parent_id.'#news'.$id);
    }

    public function show($parent_id) {
        $page_list        = $this->_page_mapper->get_all_pages();
        $page_select    = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $list            = $this->_news_mapper->get_all_objects((int)$parent_id);

        $this->template_data['page_select'] = $page_select;
        $this->template_data['list']        = $list;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['news_index'], $this->template_data);
    }

    public function add_news($parent_id = 0) {
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле "%s" незаполнено');
        $this->form_validation->set_message('is_natural', 'поле "%s" должно быть числовым');
        $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
        $this->form_validation->set_rules('anno', '<b>аннонс</b>','trim|required');
        $this->form_validation->set_rules('description', '<b>описание</b>','trim|required');
        $this->form_validation->set_rules('user_min', '<b>время публикации - минуты</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_hour', '<b>время публикации - час</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_day', '<b>время публикации - день</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_month', '<b>время публикации - месяц</b>','trim|required|is_natural');
        $this->form_validation->set_rules('user_year', '<b>время публикации - год</b>','trim|required|is_natural');

        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';

        $category = $this->_news_mapper->get_category($parent_id);

        $page_list          = $this->_page_mapper->get_all_pages();
        $page_select        = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], (int)$parent_id);
        $item               = new News_item();
        if ($this->form_validation->run() != FALSE) {
            if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                $small_image = new Image_item();
                $small_image->doUpload(NEWS_IMAGE_MAX_WIDTH, NEWS_IMAGE_MAX_HEIGHT, 'image', 'gif|jpg|png', NEWS_IMAGE_MAX_SIZE, 'news');
                $small_id = $small_image->Save();
                $small_image->createThumbnail($category->resize_width, $category->resize_height, 'news');
            }
            $item->parent_id      = (int)$parent_id;
            $item->title          = $this->input->post('title');
            $item->anno           = $this->input->post('anno');

            $item->is_spec_link   = $this->input->post('is_spec_link') == 'on' ? 1 : 0;
            $item->spec_link      = $this->input->post('spec_link');

            $item->description    = $this->input->post('description');
            $item->description    = str_replace('<div>', '<p>', $item->description);
            $item->description    = str_replace('</div>', '</p>', $item->description);
            $item->user_day       = $this->input->post('user_day');
            $item->user_month     = $this->input->post('user_month');
            $item->user_year      = $this->input->post('user_year');
            $item->user_hour      = $this->input->post('user_hour');
            $item->user_min       = $this->input->post('user_min');
            $item->image_id       = $small_id;
            $item->inner_image    = $this->input->post('inner_image');
            $item->inner_position = $this->input->post('inner_position');
            $this->_news_mapper->save($item, 'item');
            $parent_id = (int)$parent_id > 0 ? $parent_id = (int)$parent_id : '';
            redirect(base_url().'admin/news/show/'.$parent_id);
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['news_add'], $this->template_data);
    }

    public function edit_news($id = 0, $parent_id = 0) {
        if ((int)$id == 0) redirect(base_url().'admin/newsblock/'.$parent_id);
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckeditor.js';
        $this->scripts[]    = base_url().'js/plugins/ckeditor/ckfinder.js';
        $this->css[]        = base_url().'js/plugins/ckeditor/sample.css';
        $item               = $this->_news_mapper->get_object($id);
        $small_image        = new Image_item($item->image_id);
        $data = array();
        $data['id']             = $item->id;
        $data['title']          = $item->title;
        $data['anno']           = $item->anno;

        $data['is_spec_link']   = $item->is_spec_link;
        $data['spec_link']      = $item->spec_link;

        $data['description']    = $item->description;
        $data['user_day']       = $item->user_day;
        $data['user_month']     = $item->user_month;
        $data['user_year']      = $item->user_year;
        $data['user_hour']      = $item->user_hour;
        $data['user_min']       = $item->user_min;
        $data['inner_image']    = $item->inner_image;
        $data['inner_position'] = $item->inner_position;
        $data['path']           = $this->_path_to_image;
        $data['image']          = $small_image->getFilenameThumb();
        $page_list              = $this->_page_mapper->get_all_pages();
        $page_select            = $this->_get_pages_tree($page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $item->parent_id);

        $category = $this->_news_mapper->get_category($parent_id);

        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_message('is_natural', 'поле "%s" должно быть числовым');
            $this->form_validation->set_rules('title', '<b>название</b>','trim|required');
            $this->form_validation->set_rules('anno', '<b>аннонс</b>','trim|required');
            $this->form_validation->set_rules('user_min', '<b>время публикации - минуты</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_hour', '<b>время публикации - час</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_day', '<b>время публикации - день</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_month', '<b>время публикации - месяц</b>','trim|required|is_natural');
            $this->form_validation->set_rules('user_year', '<b>время публикации - год</b>','trim|required|is_natural');
            $this->form_validation->set_rules('description', '<b>описание</b>','trim|required');
            if ($this->form_validation->run() != FALSE) {
                if (!empty($_FILES) && $_FILES['image']['error'] == 0) {
                    $image            = new Image_item();
                    $image->doUpload(NEWS_IMAGE_MAX_WIDTH, NEWS_IMAGE_MAX_HEIGHT, 'image', 'gif|jpg|png', NEWS_IMAGE_MAX_SIZE, 'news');
                    $image->createThumbnail($category->resize_width, $category->resize_height, 'news');
                    $image_id        = $image->Save();
                    $item->image_id  = $image_id;
                }
                $item->parent_id        = (int)$parent_id;
                $item->title            = $this->input->post('title');
                $item->anno             = $this->input->post('anno');
                $item->description      = $this->input->post('description');

                $item->is_spec_link   = $this->input->post('is_spec_link') == 'on' ? 1 : 0;
                $item->spec_link      = $this->input->post('spec_link');

                $item->description      = str_replace('<div>', '<p>', $item->description);
                $item->description      = str_replace('</div>', '</p>', $item->description);
                $item->user_day         = $this->input->post('user_day');
                $item->user_month       = $this->input->post('user_month');
                $item->user_year        = $this->input->post('user_year');
                $item->user_hour        = $this->input->post('user_hour');
                $item->user_min         = $this->input->post('user_min');
                $item->inner_image      = $this->input->post('inner_image');
                $item->inner_position   = $this->input->post('inner_position');
                $this->_news_mapper->save($item, 'item');
                redirect(base_url().'admin/news/show/'.$parent_id.'#news'.(int)$id);
            }
        }

        $this->template_data['page_select'] = $page_select;
        $this->template_data['data']        = $data;
        $this->template_data['parent_id']   = $parent_id;

        $this->load->admin_view($this->_templates['news_edit'], $this->template_data);
    }

    public function delete_image($id, $parent_id) {
        $item = $this->_news_mapper->get_object((int)$id);
        $item->image_id = 0;
        $this->_news_mapper->save($item, 'item');
        redirect(base_url().'admin/news/edit_news/'.(int)$id.'/'.(int)$parent_id);
    }

    public function delete_news($id, $parent_id) {
        $this->_news_mapper->delete((int)$id, 'item');
        redirect(base_url().'admin/news/show/'.(int)$parent_id.'#news'.(int)$id);
    }
}
