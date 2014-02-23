<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{

    public function __construct()
    {
        parent::__construct();
    }

    public function model ( $model, $name = '', $db_conn = FALSE )
    {
        if ( is_array($model) )
        {
            foreach ( $model as $babe )
            {
                $this->model($babe);
            }
            return;
        }

        $custom_models_directory = APP_MODELS_PATH.THEME_CUSTOM_MODELS_PATH.'/';

        if ( file_exists($custom_models_directory.$model.EXT) )
        {
            $model = THEME_CUSTOM_MODELS_PATH.'/'.$model;
        } else {
            $model = THEME_DEFAULT_MODELS_PATH.'/'.$model;
        }

        parent::model($model, $name, $db_conn);
    }

    public function view ( $view, $vars = array(), $return = FALSE, $full = TRUE )
    {
        $custom_theme_path  = APP_VIEWS_PATH.THEME_CUSTOM_VIEWS_PATH.'/';
        $default_theme_path = APP_VIEWS_PATH.THEME_DEFAULT_VIEWS_PATH.'/';

        if ( ! $return )
        {
            $header = 'header';
            $footer = 'footer';

            $view_type = reset(explode('/', $view));

            // .../application/views/CUSTOM_THEME/VIEW_TYPE/VIEW_header.php
            if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.$view.'_header.php') )
            {
                $header = THEME_CUSTOM_VIEWS_PATH.'/'.$view.'_header';
            }
            // .../application/views/CUSTOM_THEME/VIEW_TYPE/header.php
            else if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.$view_type.'/'.$header.EXT) )
            {
                $header = THEME_CUSTOM_VIEWS_PATH.'/'.$view_type.'/'.$header;
            }
            // .../application/views/DEFAULT_THEME/VIEW_TYPE/VIEW_header.php
            else if ( file_exists($default_theme_path) && file_exists($default_theme_path.$view.'_header.php') )
            {
                $header = THEME_DEFAULT_VIEWS_PATH.'/'.$view.'_header';
            }
            // .../application/views/DEFAULT_THEME/VIEW_TYPE/header.php
            else
            {
                $header = THEME_DEFAULT_VIEWS_PATH.'/'.$view_type.'/'.$header;
            }

            // footer view template
            if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.$view.'_footer.php') )
            {
                $footer = THEME_CUSTOM_VIEWS_PATH.'/'.$view.'_footer';
            }
            else if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.$view_type.'/'.$footer.EXT) )
            {
                $footer = THEME_CUSTOM_VIEWS_PATH.'/'.$view_type.'/'.$footer;
            }
            else if ( file_exists($default_theme_path) && file_exists($default_theme_path.$view.'_footer.php') )
            {
                $footer = THEME_DEFAULT_VIEWS_PATH.'/'.$view.'_footer';
            }
            else
            {
                $footer = THEME_DEFAULT_VIEWS_PATH.'/'.$view_type.'/'.$footer;
            }

        }

        // view template
        if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.$view.EXT) )
        {
            $view = THEME_CUSTOM_VIEWS_PATH.'/'.$view;
        } else {
            $view = THEME_DEFAULT_VIEWS_PATH.'/'.$view;
        }

        if ( ! $return )
        {
            if ( $full )
            {
                parent::view($header, $vars);
                parent::view($view, $vars);
                parent::view($footer, $vars);
            } else {
                parent::view($view, $vars);
            }
        } else {
            return parent::view($view, $vars, $return);
        }
    }

    public function admin_view ( $view, $vars = array(), $return = FALSE, $full = TRUE )
    {
        if ( $return )
        {
            return $this->view('admin/'.$view, $vars, $return, $full);
        } else {
            $this->view('admin/'.$view, $vars, $return, $full);
        }
    }

    public function site_view ( $view, $vars = array(), $return = FALSE, $full = TRUE )
    {
        if ( $return )
        {
            return $this->view('site/'.$view, $vars, $return, $full);
        } else {
            $this->view('site/'.$view, $vars, $return, $full);
        }
    }

    public function widgets ()
    {
        $custom_theme_path  = APP_VIEWS_PATH.THEME_CUSTOM_VIEWS_PATH.'/';
        $default_theme_path = APP_VIEWS_PATH.THEME_DEFAULT_VIEWS_PATH.'/';

        if ( file_exists($custom_theme_path) && file_exists($custom_theme_path.WIDGETS_FILE_NAME.EXT) )
        {
            $widgets_path = $custom_theme_path.WIDGETS_FILE_NAME.EXT;
        } else {
            $widgets_path = $default_theme_path.WIDGETS_FILE_NAME.EXT;
        }

        if ( file_exists($widgets_path) )
        {
            return $widgets_path;
        }
        return FALSE;
    }

    public function widget ( $view, $data )
    {
        $vars = array();
        $vars['data'] = $data;

        return $this->view('widgets/'.$view, $vars, TRUE, FALSE);
    }

}