<?php

if ( ! defined('BASEPATH') )
{
    exit('No direct script access allowed');
}

class Catalog extends Admin_Controller
{
    protected $_module;
    protected $_templates;
    protected $_url;
    protected $_links;

    protected $page_list;

    // section views
    const VIEW_CATALOG_INDEX  = 'catalog/section_index';
    const VIEW_SECTION_ADD    = 'catalog/section_add';
    const VIEW_SECTION_EDIT   = 'catalog/section_edit';
    // category views
    const VIEW_CATEGORY_INDEX = 'catalog/category_index';
    const VIEW_CATEGORY_ADD   = 'catalog/category_add';
    const VIEW_CATEGORY_EDIT  = 'catalog/category_edit';
    // item views
    const VIEW_ITEM_INDEX     = 'catalog/item_index';
    const VIEW_ITEM_ADD       = 'catalog/item_add';
    const VIEW_ITEM_EDIT      = 'catalog/item_edit';
    // image upload settings
    const IMAGE_ALLOWED_TYPES    = 'gif|jpg|jpeg|png';
    const IMAGE_UPLOAD_PATH      = 'catalog';


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('catalog/catalog_mapper', 'catalog_mapper');
        $this->load->model('page/page_mapper', 'page_mapper');

        $this->_module['title'] = 'Торговый каталог';

        // section templates
        $this->_templates['section_index'] = self::VIEW_CATALOG_INDEX;
        $this->_templates['section_add']   = self::VIEW_SECTION_ADD;
        $this->_templates['section_edit']  = self::VIEW_SECTION_EDIT;
        // category templates
        $this->_templates['category_index'] = self::VIEW_CATEGORY_INDEX;
        $this->_templates['category_add']   = self::VIEW_CATEGORY_ADD;
        $this->_templates['category_edit']  = self::VIEW_CATEGORY_EDIT;
        // items templates
        $this->_templates['item_add']   = self::VIEW_ITEM_ADD;
        $this->_templates['item_edit']   = self::VIEW_ITEM_EDIT;
        $this->_templates['item_index']   = self::VIEW_ITEM_INDEX;

        $this->_templates['page_select_list']   = 'page_select_list';
        $this->_templates['page_select_item']   = 'page_select_item';

        $this->_url['section_index'] = base_url() . 'admin/catalog';

        $this->_links['section_add'] = '/admin/catalog/section/add';
        $this->_links['category_add'] = '/admin/catalog/category/add';

        // TODO: need new TemplateData class; rewrite array to object
        $this->page_list = $this->page_mapper->get_all_pages();
        $currency_list = $this->catalog_mapper->get_currency_list();

        $this->template_data['module']['title']        = $this->_module['title'];
        $this->template_data['links']['section_index'] = $this->_url['section_index'];
        $this->template_data['links']['section_add']   = $this->_links['section_add'];
        $this->template_data['links']['category_add']  = $this->_links['category_add'];
        $this->template_data['currency_list']          = $currency_list;
    }

    public function index ()
    {

        $catalog_section_list = $this->catalog_mapper->get_section_list();

        if ( !empty($_POST) )
        {
            $this->form_validation->set_rules('cart_client_sender_email', 'письмо клиенту - от кого (почта)', 'trim|required|valid_email');
            $this->form_validation->set_rules('cart_operator_sender_email', 'письмо оператору - от кого (почта)', 'trim|required|valid_email');
            $this->form_validation->set_rules('cart_operator_email', 'письмо оператору - почта оператора', 'trim|required|valid_email');
            $this->form_validation->set_message('required', 'Поле "%s" обязательно для заполнения.');
            $this->form_validation->set_message('valid_email', 'Поле "%s" должно быть e-mail.');
            if ( $this->form_validation->run() )
            {
                $cart_data['cart_client_sender_email']   = $this->input->post('cart_client_sender_email');
                $cart_data['cart_client_sender_name']    = $this->input->post('cart_client_sender_name');
                $cart_data['cart_client_subject']        = $this->input->post('cart_client_subject');
                $cart_data['cart_client_message']        = $this->input->post('cart_client_message');
                $cart_data['cart_operator_sender_email'] = $this->input->post('cart_operator_sender_email');
                $cart_data['cart_operator_sender_name']  = $this->input->post('cart_operator_sender_name');
                $cart_data['cart_operator_subject']      = $this->input->post('cart_operator_subject');
                $cart_data['cart_operator_email']        = $this->input->post('cart_operator_email');
                $cart_data['cart_operator_message']      = $this->input->post('cart_operator_message');
                $this->manager_modules->set_cart_serttings($cart_data);
            }
        }

        $cart_settings = $this->manager_modules->settings();

        $this->_template_data('catalog_section_list', $catalog_section_list);
        $this->_template_data('cart_settings', $cart_settings);
        $this->load->admin_view($this->_templates['section_index'], $this->template_data);
    }

    private function _category_index ( $object_id = 0 )
    {
        $category_list = $this->catalog_mapper->get_section_category_tree($object_id);
        $section       = new Catalog_Section($object_id);
        $items = $this->catalog_mapper->get_section_item_list($object_id, 'priority', 'ASC');

        $this->_template_data('section', $section);
        $this->_template_data('category_list', $category_list);
        $this->_template_data('item_list', $items);
        $this->load->admin_view($this->_templates['category_index'], $this->template_data);
    }

    public function section ( $request_type = 'none', $object_id = 0 )
    {
        switch ( $request_type )
        {
            case 'add':
            $this->_section_add();
            break;

            case 'edit':
            $this->_section_edit($object_id);
            break;

            case 'del':
            $this->_section_del($object_id);
            break;

            default:
            redirect($this->_url['section_index']);
            break;
        }
    }

    public function category ( $request_type = 'none', $object_id = 0, $optional = '' )
    {
        switch ( $request_type )
        {
            case 'list':
            $this->_category_index($object_id);
            break;

            case 'add':
            $this->_category_add($object_id);
            break;

            case 'edit':
            $this->_category_edit($object_id);
            break;

            case 'del':
            $this->_category_del($this->catalog_mapper->get_object($object_id, 'category'));
            break;

            case 'items':
            $this->_category_items($object_id);
            break;

            case 'unlink':
            $this->_items_unlink($optional, $object_id);
            break;

            default:
            redirect($this->_url['section_index']);
            break;
        }
    }

    public function imgdel ( $item_id = 0, $image_id = 0 )
    {
        $this->catalog_mapper->image_delete($item_id, $image_id);
        redirect($this->_url['section_index']);
    }

    public function items ( $request_type = 'none', $object_id = 0, $optional = '' )
    {
        switch ( $request_type )
        {
            case 'list':
            $this->_items_index($object_id);
            break;

            case 'add':
            $this->_items_add($object_id);
            break;

            case 'edit':
            $this->_items_edit($object_id);
            break;

            case 'del':
            $this->_items_del($this->catalog_mapper->get_object($object_id, 'item'));
            break;

            case 'unlink':
            $this->_items_unlink($object_id, $optional);
            break;

            default:
            redirect($this->_url['section_index']);
            break;
        }
    }

    private function _items_unlink ( $item_id, $category_id )
    {
        $this->catalog_mapper->unlink($item_id, $category_id);
        redirect($this->_url['section_index']);
    }

    private function _items_index ( $item_id )
    {
        $item       = $this->catalog_mapper->get_object($item_id, 'item');
        $categories = $this->catalog_mapper->get_item_category_list($item_id);
        $section    = new Catalog_Section($item->section_id);

        $this->_template_data('item', $item);
        $this->_template_data('section', $section);
        $this->_template_data('is_item', TRUE);
        $this->_template_data('categories', $categories);
        $this->load->admin_view($this->_templates['item_index'], $this->template_data);
    }

    private function _category_items ( $category_id )
    {
        $category = $this->catalog_mapper->get_object($category_id, 'category');
        $items    = $this->catalog_mapper->get_category_item_list($category_id);
        $section  = new Catalog_Section($category->parent_section_id);

        $this->_template_data('items', $items);
        $this->_template_data('section', $section);
        $this->_template_data('is_item', FALSE);
        $this->_template_data('category', $category);
        $this->load->admin_view($this->_templates['item_index'], $this->template_data);
    }

    private function _section_add ( $parent_id = 0 )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('per_page', 'Количество на страницу', 'required|integer');
            if ( $this->_set_form_validation_message('section_add') && $this->form_validation->run() )
            {
                $section = new Catalog_Section();
                $section->parent_page_id = $this->input->post('page_id');
                $section->title          = $this->input->post('title');
                $section->count_per_page = $this->input->post('per_page');
                $section->currency_id    = $this->input->post('currency_id');
                $section->similar_items  = $this->input->post('similar_items');
                $section->resize_width   = $this->input->post('resize_width');
                $section->resize_height  = $this->input->post('resize_height');
                $section_id = $this->catalog_mapper->save($section);

                $uf_title = $this->input->post('uf_title');
                $uf_type  = $this->input->post('uf_type');
                if ( !empty($uf_type) && !empty($uf_title) )
                {
                    $this->catalog_mapper->user_field_add($section_id, $uf_title, $uf_type);
                }
                redirect($this->_url['section_index']);
            }
        }
        $page_list = $this->_get_pages_tree($this->page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $parent_id);
        $this->_template_data('page_list', $page_list);
        $this->load->admin_view($this->_templates['section_add'], $this->template_data);
    }

    private function _category_add ( $parent_id )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('priority', 'Приоритет', 'required|is_natural');
            if ( $this->_set_form_validation_message('section_add') && $this->form_validation->run() )
            {
                $category = new Catalog_Category();
                $category->parent_category_id = $this->input->post('parent_category_id');
                $category->parent_section_id  = $this->input->post('parent_section_id');
                $category->title              = $this->input->post('title');
                $category->priority           = $this->input->post('priority');
                $this->catalog_mapper->save($category);

                $section = new Catalog_Section($parent_id);
                redirect(base_url($section->link('cat_list')));
            }
        }
        $section = new Catalog_Section($parent_id);
        $category_list = $this->catalog_mapper->get_category_list($parent_id);
        $this->_template_data('section', $section);
        $this->_template_data('category_list', $category_list);
        $this->load->admin_view($this->_templates['category_add'], $this->template_data);
    }

    private function _items_add ( $section_id )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('article', 'Артикул', 'trim|required');
            $this->form_validation->set_rules('price', 'Цена', 'trim|required');
            $this->form_validation->set_rules('description', 'Описание', 'trim|required');
            if ( $this->_set_form_validation_message('item_add') && $this->form_validation->run() )
            {
                $item = new Catalog_Item();
                $item->title         = $this->input->post('title');
                $item->description   = $this->input->post('description');
                $item->article       = $this->input->post('article');
                $item->priority      = $this->input->post('item_priority');

                $item->price         = str_replace(',', '.', $this->input->post('price'));
                $item->section_id    = $section_id;
                $item->in_stock      = ( $this->input->post('in_stock') == 'on' ) ? 1 : 0;
                $item->is_bestseller = ( $this->input->post('is_bestseller') == 'on' ) ? 1 : 0;
                $item->is_sale       = ( $this->input->post('is_sale') == 'on' ) ? 1 : 0;
                $imgs_id = array();
                if ( isset($_FILES) && !empty($_FILES) )
                {
                    $img_count = $this->input->post('img_count');
                    for ( $i = 1; $i <= $img_count; $i++ )
                    {
                        $img_name = 'image_' . $i;
                        if ( $_FILES[$img_name]['error'] == 0 )
                        {
                            $section = new Catalog_Section($section_id);

                            $image = new Image_item();
                            $image->doUpload(CATALOG_IMAGE_MAX_WIDTH, CATALOG_IMAGE_MAX_HEIGHT, $img_name, self::IMAGE_ALLOWED_TYPES, CATALOG_IMAGE_MAX_SIZE, self::IMAGE_UPLOAD_PATH);
                            $image_id = $image->save();
                            $imgs_id[] = $image_id;
                            $image->createThumbnail($section->resize_width, $section->resize_height, self::IMAGE_UPLOAD_PATH);
                        }
                    }
                }
                $item_id = $this->catalog_mapper->save($item);
                // add images
                $this->catalog_mapper->images_add($item_id, $imgs_id);
                // add category links
                $parent_category_list = $this->input->post('parent_category_id');
                $this->catalog_mapper->links_add($item_id, $parent_category_list);
                // add user field values
                $uf_values = $this->input->post('uf_values');
                $uf_ids    = $this->input->post('uf_ids');
                $uf_types  = $this->input->post('uf_types');
                if ( !empty($uf_values) && !empty($uf_ids) && !empty($uf_types) )
                {
                    $this->catalog_mapper->set_uf_values($item_id, $uf_values, $uf_ids, $uf_types);
                }
                $section = new Catalog_Section($section_id);
                redirect(base_url($section->link('cat_list')));
            }
        }
        $this->scripts[] = base_url('js/plugins/ckeditor/ckeditor.js');
        $this->scripts[] = base_url('js/plugins/ckfinder/ckfinder.js');

        $section = new Catalog_Section($section_id);
        $category_list = $this->catalog_mapper->get_category_list($section_id);
        $user_fields = $this->catalog_mapper->get_uf_list($section_id);

        $this->_template_data('section', $section);
        $this->_template_data('user_fields', $user_fields);
        $this->_template_data('category_list', $category_list);
        $this->load->admin_view($this->_templates['item_add'], $this->template_data);
    }

    private function _section_edit ( $object_id )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('per_page', 'Количество на страницу', 'required|integer');
            if ( $this->_set_form_validation_message('section_add') && $this->form_validation->run() )
            {
                $section = new Catalog_Section($object_id);
                $section->parent_page_id = $this->input->post('page_id');
                $section->title          = $this->input->post('title');
                $section->count_per_page = $this->input->post('per_page');
                $section->currency_id    = $this->input->post('currency_id');
                $section->similar_items  = $this->input->post('similar_items');
                $section->resize_width   = $this->input->post('resize_width');
                $section->resize_height  = $this->input->post('resize_height');
                $this->catalog_mapper->save($section);
                // new user fields
                $uf_title = $this->input->post('uf_title');
                $uf_type  = $this->input->post('uf_type');
                if ( !empty($uf_type) && !empty($uf_title) )
                {
                    $this->catalog_mapper->user_field_add($section->id, $uf_title, $uf_type);
                }
                // old user fields
                $cur_uf_title = $this->input->post('cur_uf_title');
                $cur_uf_id    = $this->input->post('cur_uf_id');
                if ( !empty($cur_uf_title) && !empty($cur_uf_id) )
                {
                    $this->catalog_mapper->user_field_upd($section->id, $cur_uf_title, $cur_uf_id);
                }
                redirect($this->_url['section_index']);
            }
        }
        $section = new Catalog_Section($object_id);
        $page_list = $this->_get_pages_tree($this->page_list, $this->_templates['page_select_list'], $this->_templates['page_select_item'], $section->parent_page_id);
        $user_fields = $this->catalog_mapper->get_uf_list($section->id);
        $this->_template_data('section', $section);
        $this->_template_data('user_fields', $user_fields);
        $this->_template_data('page_list', $page_list);
        $this->load->admin_view($this->_templates['section_edit'], $this->template_data);
    }

    private function _category_edit ( $object_id )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('priority', 'Приоритет', 'required|is_natural');
            if ( $this->_set_form_validation_message('section_add') && $this->form_validation->run() )
            {
                $category = $this->catalog_mapper->get_object($object_id, 'category');
                $category->parent_category_id = $this->input->post('parent_category_id');
                $category->parent_section_id  = $this->input->post('parent_section_id');
                $category->title              = $this->input->post('title');
                $category->priority           = $this->input->post('priority');
                $this->catalog_mapper->save($category);

                $section = new Catalog_Section($category->parent_section_id);
                redirect(base_url($section->link('cat_list')));
            }
        }
        $category = $this->catalog_mapper->get_object($object_id, 'category');
        $section = new Catalog_Section($category->parent_section_id);
        $category_list = $this->catalog_mapper->get_category_list($category->parent_section_id);
        $this->_template_data('section', $section);
        $this->_template_data('category', $category);
        $this->_template_data('category_list', $category_list);
        $this->load->admin_view($this->_templates['category_edit'], $this->template_data);
    }

    private function _items_edit ( $object_id  )
    {
        if ( $this->input->post('cmd') )
        {
            $this->form_validation->set_rules('title', 'Название', 'trim|required');
            $this->form_validation->set_rules('article', 'Артикул', 'trim|required');
            $this->form_validation->set_rules('price', 'Цена', 'trim|required');
            $this->form_validation->set_rules('description', 'Описание', 'trim|required');
            if ( $this->_set_form_validation_message('item_add') && $this->form_validation->run() )
            {
                // save new item
                $item = $this->catalog_mapper->get_object($object_id, 'item');
                $item->title         = $this->input->post('title');
                $item->description   = $this->input->post('description');
                $item->article       = $this->input->post('article');
                $item->priority      = $this->input->post('item_priority');

                $item->price         = str_replace(',', '.', $this->input->post('price'));
                $item->in_stock      = ( $this->input->post('in_stock') == 'on' ) ? 1 : 0;
                $item->is_bestseller = ( $this->input->post('is_bestseller') == 'on' ) ? 1 : 0;
                $item->is_sale       = ( $this->input->post('is_sale') == 'on' ) ? 1 : 0;
                $this->catalog_mapper->save($item);
                // images
                $imgs_id = array();
                if ( isset($_FILES) && !empty($_FILES) )
                {
                    $img_count = $this->input->post('img_count');
                    for ( $i = 1; $i <= $img_count; $i++ )
                    {
                        $img_name = 'image_' . $i;
                        if ( $_FILES[$img_name]['error'] == 0 )
                        {
                            $section = new Catalog_Section($item->section_id);

                            $image = new Image_item();
                            $image->doUpload(CATALOG_IMAGE_MAX_WIDTH, CATALOG_IMAGE_MAX_HEIGHT, $img_name, self::IMAGE_ALLOWED_TYPES, CATALOG_IMAGE_MAX_SIZE, self::IMAGE_UPLOAD_PATH);
                            $image_id = $image->save();
                            $imgs_id[] = $image_id;
                            $image->createThumbnail($section->resize_width, $section->resize_height, self::IMAGE_UPLOAD_PATH);
                        }
                    }
                }
                $this->catalog_mapper->images_edit($item->id, $imgs_id);

                $cur_imgs     = $this->input->post('cur_ids');
                $cur_priority = $this->input->post('priority');
                $main_img     = $this->input->post('is_main');
                $this->catalog_mapper->images_save($item->id, $cur_imgs, $cur_priority, $main_img);

                // links
                $parent_category_list = $this->input->post('parent_category_id');
                if ( ! $parent_category_list )
                {
                    $parent_category_list = array();
                }
                $this->catalog_mapper->links_edit($item->id, $parent_category_list);
                // add user field values
                $form_type = $this->input->post('form_type');
                $uf_values = $this->input->post('uf_values');
                $uf_ids    = $this->input->post('uf_ids');
                $uf_types  = $this->input->post('uf_types');
                if ( !empty($uf_values) && !empty($uf_ids) && !empty($uf_types) )
                {
                    if ( $form_type )
                    {
                        $this->catalog_mapper->set_uf_values($item->id, $uf_values, $uf_ids, $uf_types);
                    } else {
                        $this->catalog_mapper->upd_uf_values($item->id, $uf_values, $uf_ids, $uf_types);
                    }
                }
                $section = new Catalog_Section($item->section_id);
                redirect(base_url($section->link('cat_list')));
            }
        }
        $this->scripts[] = base_url('js/plugins/ckeditor/ckeditor.js');
        $this->scripts[] = base_url('js/plugins/ckfinder/ckfinder.js');

        $item = $this->catalog_mapper->get_object($object_id, 'item');
        $item_links = $this->catalog_mapper->get_item_links($item->id);
        $item_images = $this->catalog_mapper->get_item_images($item->id);
        $section = new Catalog_Section($item->section_id);
        $category_list = $this->catalog_mapper->get_category_list($section->id);
        $user_values = $this->catalog_mapper->get_uf_values($section->id, $item->id);
        $user_fields = $this->catalog_mapper->get_uf_list($section->id);

        $section_list = $this->catalog_mapper->get_section_list();
        $this->_template_data('section_list', $section_list);
        $similar_items = $this->catalog_mapper->get_similar_items($item->id);
        $this->_template_data('similar_items', $similar_items);

        $this->_template_data('user_values', $user_values);
        $this->_template_data('user_fields', $user_fields);
        $this->_template_data('item', $item);
        $this->_template_data('item_links', $item_links);
        $this->_template_data('item_images', $item_images);
        $this->_template_data('section', $section);
        $this->_template_data('category_list', $category_list);
        $this->load->admin_view($this->_templates['item_edit'], $this->template_data);
    }

    private function _section_del ( $object_id )
    {
        $this->catalog_mapper->delete($object_id, 'section');
        redirect($this->_url['section_index']);
    }

    private function _category_del ( $object )
    {
        $this->catalog_mapper->delete($object, 'category');
        redirect($this->_url['section_index']);
    }

    private function _items_del ( $object )
    {
        $this->catalog_mapper->delete($object, 'item');
        $section = new Catalog_Section($object->section_id);
        redirect(base_url($section->link('cat_list')));
    }

    private function _set_form_validation_message ( $config )
    {
        $this->form_validation->set_error_delimiters('', '<br />');
        switch ( $config )
        {
            case 'section_add':
                $this->form_validation->set_message('required', 'Поле "%s" обязательно для заполнения.');
                $this->form_validation->set_message('integer', 'Поле "%s" должно содержать только цифры.');
                $this->form_validation->set_message('is_natural', 'Поле "%s" должно содержать только целые положительные числа.');
            break;

            case 'item_add':
                $this->form_validation->set_message('required', 'Поле "%s" обязательно для заполнения.');
            break;

            default:
                return FALSE;
        }
        return TRUE;
    }

    private function _template_data ( $data = array(), $data_value = NULL )   // TODO: need new TemplateData class; rewrite array to object
    {
        if ( is_array($data) && ! empty($data) )
        {
            $this->template_data[] = $data;
        } else {
            $this->template_data[$data] = $data_value;
        }
    }

    public function get_categories ( $section_id = 0 )
    {
        $cat_list = $this->catalog_mapper->get_category_list($section_id);
        echo json_encode($cat_list);
    }

    public function get_items ( $category_id = 0, $section_id = 0 )
    {
        if ( $category_id )
        {
            $item_list = $this->catalog_mapper->get_category_item_list($category_id);
        } else {
            $item_list = $this->catalog_mapper->get_section_item_list($section_id);
        }
        echo json_encode($item_list);
    }

    public function set_similar ()
    {
        $item_id     = $this->input->post('item_id');
        $sim_item_id = $this->input->post('sim_item_id');
        $result = array();
        if ( $item_id && $sim_item_id )
        {
            if ( $this->catalog_mapper->set_similar_items($item_id, $sim_item_id) )
            {
                $result['msg']  = 'Товар добавлен успешно!';
                $result['code'] = 1;
            } else {
                $result['msg']  = 'Товар уже был добавлен ранее!';
                $result['code'] = 2;
            }
        } else {
            $result['msg']  = 'Произошла ошибка!';
            $result['code'] = 3;
        }
        echo json_encode($result);
    }

    public function unset_similar ( $item_id = 0, $sim_item_id = 0 )
    {
        if ( $item_id && $sim_item_id )
        {
            $this->catalog_mapper->unset_similar_items($item_id, $sim_item_id);
            redirect($this->_url['section_index']);
        }
    }

    // ========================================================
    private function _debug( $value, $exit = true )
    {
        print('<pre>');
        print_r($value);
        print('</pre>');
        if ( $exit )
        {
            exit();
        }
    }
    // ========================================================

}