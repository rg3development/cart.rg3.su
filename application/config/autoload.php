<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
*/

$autoload['libraries'] = array();

$autoload['libraries'][] = 'database';
$autoload['libraries'][] = 'session';
$autoload['libraries'][] = 'form_validation';
$autoload['libraries'][] = 'loader';
$autoload['libraries'][] = 'pagination';
$autoload['libraries'][] = 'image_lib';
$autoload['libraries'][] = 'uri';
$autoload['libraries'][] = 'upload';
$autoload['libraries'][] = 'email';

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
*/

$autoload['helper'] = array();

$autoload['helper'][] = 'url';
$autoload['helper'][] = 'file';
$autoload['helper'][] = 'form';
$autoload['helper'][] = 'security';
$autoload['helper'][] = 'cms_helper';

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
*/

$autoload['config'] = array();

$autoload['config'][] = 'cms_config';

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
*/

$autoload['model'] = array();

$autoload['model'][] = 'banner/banner_mapper';
$autoload['model'][] = 'cart/cart_mapper';
$autoload['model'][] = 'catalog/catalog_mapper';
$autoload['model'][] = 'comments/comments_mapper';
$autoload['model'][] = 'feedback/feedback_mapper';
$autoload['model'][] = 'gallery/gallery_mapper';
$autoload['model'][] = 'news/news_mapper';
$autoload['model'][] = 'page/page_mapper';
$autoload['model'][] = 'response/response_mapper';
$autoload['model'][] = 'search/search_mapper';
$autoload['model'][] = 'sitemap/sitemap_mapper';
$autoload['model'][] = 'text/text_mapper';
$autoload['model'][] = 'image';
$autoload['model'][] = 'image_item';
$autoload['model'][] = 'manager_modules';
$autoload['model'][] = 'geo_core/shop_mapper';