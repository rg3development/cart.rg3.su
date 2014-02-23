<?php

class MY_Controller extends CI_Controller {

    protected $scripts = array();
    protected $css     = array();
    protected $menu    = array();

    public function __construct() {
        parent::__construct();
    }

    protected function _get_pages_tree($map_array = array(), $list_tmp = '', $item_tmp = '', $active_id = 0, $params = array()) {
        if ( !is_array($map_array) || (sizeof($map_array) == 0) ) {
            return false;
        }
        if ( empty($list_tmp) ) {
            $list_tmp = $this->_templates['map_list'];
        }
        if ( empty($item_tmp) ) {
            $item_tmp = $this->_templates['map_item'];
        }

        $map       = '';
        $max_level = sizeof($map_array) - 1;

        for ($i = $max_level; $i >= 0 ; $i--)
        {
            if ( !empty($map_array[$i]) && sizeof($map_array[$i] ) > 0)
            {
                foreach ( $map_array[$i] as $key => $page )
                {
                    $j = $i + 1;
                    $tmp_submenu = '';
                    if ( !empty($html_menu[$j]) && sizeof($html_menu[$j]) > 0 )
                    {
                        foreach($html_menu[$j] as $key => $subpage)
                        {
                            if ($page->id == $subpage['parent_id'])
                            {
                                $tmp_submenu .= $subpage['string'];
                            }
                        }
                    }
                    if ( !empty($tmp_submenu) )
                    {
                        $tmp_submenu = $this->load->site_view($list_tmp, array('pages_block' => $tmp_submenu, 'params' => $params), true);
                    }

                    $content_data['page']      = $page;
                    $content_data['submenu']   = $tmp_submenu;
                    $content_data['level']     = $i;
                    $content_data['active_id'] = (int) $active_id;
                    $content_data['params']    = $params;

                    $html_menu[$i][] = array (
                        'parent_id' => $page->parent_id,
                        'string'    => $this->load->site_view($item_tmp, $content_data, TRUE)
                    );
                }
            }
        }

        if (!empty($html_menu[0])) {
            foreach ($html_menu[0] as $menu_item) {
                $map .= $menu_item['string'];
            }
        } else {
            $map = '';
        }
        return $map;
    }
}

class Admin_Controller extends CI_Controller
{

    protected $scripts       = array();
    protected $css           = array();
    protected $template_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');

        if ( ! $this->auth_model->is_auth() )
        {
            redirect (base_url('admin/auth/login'));
        }
        $this->_is_auth = $this->auth_model->is_auth();

        // CSS
        $this->css[] = base_url('www_admin/css/style.css');
        // JS
        $this->scripts[] = base_url('www_admin/js/jquery-1.7.min.js');
        $this->scripts[] = base_url('www_admin/js/jquery-ui-1.8.16.custom.min.js');
        // Plugins
        $this->css[]     = base_url('plugins/formstyler/jquery.formstyler.css');
        $this->scripts[] = base_url('plugins/formstyler/jquery.formstyler.min.js');
        $this->scripts[] = base_url('plugins/ckeditor/ckeditor.js');

        $this->template_data['css']     = $this->css;
        $this->template_data['scripts'] = $this->scripts;
        $this->template_data['menu']    = $this->config->item('admin_menu');
    }

    protected function _get_pages_tree($map_array = array(), $list_tmp = '', $item_tmp = '', $active_id = 0, $params = array()) {
        if ( !is_array($map_array) || (sizeof($map_array) == 0) ) {
            return false;
        }
        if ( empty($list_tmp) ) {
            $list_tmp = $this->_templates['map_list'];
        }
        if ( empty($item_tmp) ) {
            $item_tmp = $this->_templates['map_item'];
        }

        $map       = '';
        $max_level = sizeof($map_array) - 1;

        for ($i = $max_level; $i >= 0 ; $i--)
        {
            if ( !empty($map_array[$i]) && sizeof($map_array[$i] ) > 0)
            {
                foreach ( $map_array[$i] as $key => $page )
                {
                    $j = $i + 1;
                    $tmp_submenu = '';
                    if ( !empty($html_menu[$j]) && sizeof($html_menu[$j]) > 0 )
                    {
                        foreach($html_menu[$j] as $key => $subpage)
                        {
                            if ($page->id == $subpage['parent_id'])
                            {
                                $tmp_submenu .= $subpage['string'];
                            }
                        }
                    }
                    if ( !empty($tmp_submenu) )
                    {
                        $tmp_submenu = $this->load->admin_view($list_tmp, array('pages_block' => $tmp_submenu, 'params' => $params), true);
                    }

                    $content_data['page']      = $page;
                    $content_data['submenu']   = $tmp_submenu;
                    $content_data['level']     = $i;
                    $content_data['active_id'] = (int) $active_id;
                    $content_data['params']    = $params;

                    $html_menu[$i][] = array (
                        'parent_id' => $page->parent_id,
                        'string'    => $this->load->admin_view($item_tmp, $content_data, TRUE)
                    );
                }
            }
        }

        if (!empty($html_menu[0])) {
            foreach ($html_menu[0] as $menu_item) {
                $map .= $menu_item['string'];
            }
        } else {
            $map = '';
        }
        return $map;
    }

}