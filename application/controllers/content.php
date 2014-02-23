<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

  protected $_contact_text_id;

  public function  __construct() {
    parent::__construct();
    $this->templates['menu_list']    = 'menu/top_list';
    $this->templates['menu_item']    = 'menu/top_item';
    $this->templates['sitemap_list'] = 'sitemap/list';
    $this->templates['sitemap_item'] = 'sitemap/item';
  }

  public function index() {
    $this->_dispatch();
  }

  public function check_phone_number ( $phone )
  {
      if ( ! preg_match("/^(\+?\d+)?\s*(\(\d+\))?[\s-]*([\d-]*)$/", $phone) )
      {
          $this->form_validation->set_message('check_phone_number', 'поле <strong>%s</strong> имеет неверный формат');
          return FALSE;
      }
      return TRUE;
  }

  public function rss() {
    $rss = $this->news_mapper->get_rss();
    ob_clean();
    print($rss);
  }

  public function yml() {
    $yml = $this->catalog_mapper->get_yml();
    ob_clean();
    print($yml);
  }

  public function error404() {
    $this->_dispatch('/content/error404');
  }

  protected function _dispatch($init_url = '') {
    $page_data = array();

    $widgets = $this->load->widgets();
    if ( $widgets )
    {
      include_once($widgets);
    }

    $active_url = uri_string();

    // content for main
    if (empty($active_url)) $active_url = '/main';
    if (!empty($init_url)) $active_url = $init_url;
    if (substr($active_url, 0, 1) == '/') $active_url = substr($active_url, 1);
    if (substr($active_url, -1, 1) == '/') $active_url = substr($active_url, 0, -1);
    $page_info    = $this->manager_modules->get_page_data($active_url);

    // page parent ids
    $parent_ids = $this->page_mapper->get_parent_ids($page_info['page']['id']);

    $menu_array = $this->page_mapper->get_menu();
    $menu       = $this->_get_pages_tree($menu_array, $this->templates['menu_list'], $this->templates['menu_item'], 0, array('parent_ids' => $parent_ids));

    if ($page_info['page']['level'] == 0) {
      $submenu  = $this->page_mapper->get_menu(1, $page_info['page']['id']);
    } else {
      $submenu  = $this->page_mapper->get_menu(1, $page_info['page']['parent_id']);
    }
    $edit_image_bottom = new Image_item($page_info['page']['image_bottom']);
    if ((int)$page_info['site_settings']['logo'] > 0) {
      $site_logo_image = new Image_item($page_info['site_settings']['logo']);
      $page_info['site_settings']['logo'] = IMAGESRC.'settings/'.$site_logo_image->getFilename();
      unset($site_logo_image);
    } else {
      $page_info['site_settings']['logo'] = '';
    }
    $page_info['page']['image_bottom']    = $edit_image_bottom->getFilename();
    $page_info['page']['path_to_image']   = $this->page_mapper->get_path_to_image();
    $content_html = '';
    foreach($page_info['modules'] as $module) {
      $class_names  = !empty($module['classname']) ? $module['classname'] : '';
      if (!empty($class_names)) {
        $objects    = new $class_names;
        $content_html .= $objects->get_page_content($page_info['page']['id']);
      }
    }
    $counters = $this->manager_modules->settings();

    $page_data['menu']          = $menu;
    $page_data['submenu']       = $submenu;
    $page_data['page_info']     = $page_info['page'];
    $page_data['site_settings'] = $page_info['site_settings'];
    $page_data['content']       = $content_html;
    $page_data['counters']      = $counters['SITE_COUNTERS'];

    $template = $page_info['page']['template'];

    $this->load->site_view($template, $page_data);
  }

}
