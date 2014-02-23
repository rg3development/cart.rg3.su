<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| PAGE LAYOUTS
|--------------------------------------------------------------------------
*/
$config['page_layouts']['inner']    = 'О продукте';
$config['page_layouts']['contacts'] = 'Магазины';
$config['page_layouts']['main']     = 'Главная';

/*
|--------------------------------------------------------------------------
| ADMIN MAIN MENU
|--------------------------------------------------------------------------
*/
$config['admin_menu']['admin/map']         = 'карта сайта';
$config['admin_menu']['admin/text']        = 'текстовые блоки';
// $config['admin_menu']['admin/news']        = 'списки';
// $config['admin_menu']['admin/response']    = 'отзывы';
// $config['admin_menu']['admin/gallery']     = 'галереи';
$config['admin_menu']['admin/banner']      = 'виджеты';
// $config['admin_menu']['admin/catalog']     = 'каталог';
// $config['admin_menu']['admin/feedback']    = 'ФОС';
// $config['admin_menu']['admin/comments']    = 'комментарии';
$config['admin_menu']['admin/geo_shops']      = 'магазины';
$config['admin_menu']['admin/settings']    = 'настройка';
$config['admin_menu']['admin/auth/logout'] = 'выход';

/*
|--------------------------------------------------------------------------
| DEFAULT PAGINATION
|--------------------------------------------------------------------------
*/

$default_pagination = array();
// The number of "digit" links
$default_pagination['num_links']       = 2;
// Hiding the Pages
$default_pagination['display_pages']   = TRUE;
// Adding Enclosing Markup
$default_pagination['full_tag_open']   = '';
$default_pagination['full_tag_close']  = '';
// Customizing the First Link
$default_pagination['first_link']      = '<<';
$default_pagination['first_tag_open']  = '';
$default_pagination['first_tag_close'] = '';
// Customizing the Last Link
$default_pagination['last_link']       = '>>';
$default_pagination['last_tag_open']   = '';
$default_pagination['last_tag_close']  = '';
// Customizing the "Next" Link
$default_pagination['next_link']       = '>';
$default_pagination['next_tag_open']   = '';
$default_pagination['next_tag_close']  = '';
// Customizing the "Previous" Link
$default_pagination['prev_link']       = '<';
$default_pagination['prev_tag_open']   = '';
$default_pagination['prev_tag_close']  = '';
// Customizing the "Current Page" Link
$default_pagination['cur_tag_open']    = '';
$default_pagination['cur_tag_close']   = '';
// Customizing the "Digit" Link
$default_pagination['num_tag_open']    = '';
$default_pagination['num_tag_close']   = '';
// Adding a class to every anchor
$default_pagination['anchor_class']    = '';

$config['default_pagination']  = $default_pagination;

/*
|--------------------------------------------------------------------------
| CUSTOM PAGINATION
|--------------------------------------------------------------------------
*/

$custom_pagination = array();
// The number of "digit" links
$custom_pagination['num_links']       = 2;
// Hiding the Pages
$custom_pagination['display_pages']   = TRUE;
// Adding Enclosing Markup
$custom_pagination['full_tag_open']   = '';
$custom_pagination['full_tag_close']  = '';
// Customizing the First Link
$custom_pagination['first_link']      = '<<';
$custom_pagination['first_tag_open']  = '';
$custom_pagination['first_tag_close'] = '';
// Customizing the Last Link
$custom_pagination['last_link']       = '>>';
$custom_pagination['last_tag_open']   = '';
$custom_pagination['last_tag_close']  = '';
// Customizing the "Next" Link
$custom_pagination['next_link']       = '>';
$custom_pagination['next_tag_open']   = '';
$custom_pagination['next_tag_close']  = '';
// Customizing the "Previous" Link
$custom_pagination['prev_link']       = '<';
$custom_pagination['prev_tag_open']   = '';
$custom_pagination['prev_tag_close']  = '';
// Customizing the "Current Page" Link
$custom_pagination['cur_tag_open']    = '';
$custom_pagination['cur_tag_close']   = '';
// Customizing the "Digit" Link
$custom_pagination['num_tag_open']    = '';
$custom_pagination['num_tag_close']   = '';
// Adding a class to every anchor
$custom_pagination['anchor_class']    = '';

/*
|--------------------------------------------------------------------------
| MODULES PAGINATION
|--------------------------------------------------------------------------
*/

$config['pagination_catalog']  = $default_pagination;
$config['pagination_gallery']  = $default_pagination;
$config['pagination_news']     = $default_pagination;
$config['pagination_response'] = $default_pagination;