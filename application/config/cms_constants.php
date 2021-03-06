<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
    SETUP CONSTANTS:

    Настройка окружения      = ENVIRONMENT
    Настройка БД             = DB_USERNAME, DB_PASSWORD, DB_DATABASE
    Настройка директорий тем = THEME_CUSTOM_VIEWS_PATH, THEME_CUSTOM_MODELS_PATH
*/

/*
|--------------------------------------------------------------------------
| ENVIRONMENT CONSTS (ERROR REPORTING)
|--------------------------------------------------------------------------
|   dev | prod
|
*/

define('ENVIRONMENT', 'prod');

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
|--------------------------------------------------------------------------
*/

define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Rb9Ls64');
define('DB_DATABASE', 'cart.rg3.su');

define('DB_DBDRIVER', 'mysql');
define('DB_DBPREFIX', '');
define('DB_PCONNECT', FALSE);
define('DB_DB_DEBUG', TRUE);
define('DB_CACHE_ON', FALSE);
define('DB_CACHEDIR', '');
define('DB_CHAR_SET', 'utf8');
define('DB_DBCOLLAT', 'utf8_general_ci');
define('DB_SWAP_PRE', '');
define('DB_AUTOINIT', TRUE);
define('DB_STRICTON', FALSE);

/*
|--------------------------------------------------------------------------
| APPLICATION PATH
|--------------------------------------------------------------------------
*/

define('APP_VIEWS_PATH', APPPATH.'views/');
define('APP_MODELS_PATH', APPPATH.'models/');
define('APP_CONFIG_PATH', APPPATH.'config/');
define('APP_CONTROLLERS_PATH', APPPATH.'controllers/');

/*
|--------------------------------------------------------------------------
| THEME CONSTS
|--------------------------------------------------------------------------
*/

define('THEME_CUSTOM_VIEWS_PATH', 'ultraglide.ru');
define('THEME_CUSTOM_MODELS_PATH', 'ultraglide.ru');

define('THEME_DEFAULT_VIEWS_PATH', 'default');
define('THEME_DEFAULT_MODELS_PATH', 'default');

/*
|--------------------------------------------------------------------------
| CONTENT WIDGETS CONSTS
|--------------------------------------------------------------------------
*/

define('WIDGETS_FILE_NAME', 'widgets');

/*
|--------------------------------------------------------------------------
| IMAGE AND FILE UPLOAD CONSTS
|--------------------------------------------------------------------------
*/

define('IMAGESRC','/upload/images/');
define('IMAGEPATH', FCPATH.'upload/images/');

define('EDITORSRC','/upload/editor/');
define('EDITORPATH', FCPATH.'upload/editor/');

/*
|--------------------------------------------------------------------------
| UPLOAD IMAGES PATH
|--------------------------------------------------------------------------
*/

define('UPLOAD_BANNER_IMAGE', IMAGESRC.'banner');
define('UPLOAD_CATALOG_IMAGE', IMAGESRC.'catalog');

/*
|--------------------------------------------------------------------------
| GALLERY IMAGE CONSTS
|--------------------------------------------------------------------------
*/

define('GALLERY_UPLOAD_W', 3000);
define('GALLERY_UPLOAD_H', 3000);
define('GALLERY_UPLOAD_SIZE', 2048);
define('GALLERY_IMAGE_TOOLTIP', sprintf('фиксированный размер %dx%d px не более %d kB', GALLERY_UPLOAD_W, GALLERY_UPLOAD_H, GALLERY_UPLOAD_SIZE));

/*
|--------------------------------------------------------------------------
| BANNER IMAGE CONSTS
|--------------------------------------------------------------------------
*/

define('BANNER_IMAGE_MAX_WIDTH', 3000);
define('BANNER_IMAGE_MAX_HEIGHT', 3000);
define('BANNER_IMAGE_MAX_SIZE', 2048);
define('BANNER_MAIN_IMAGE_TOOLTIP', sprintf('фиксированный размер %dx%d px не более %d kB', BANNER_IMAGE_MAX_WIDTH, BANNER_IMAGE_MAX_HEIGHT, BANNER_IMAGE_MAX_SIZE));

/*
|--------------------------------------------------------------------------
| NEWS IMAGE CONSTS
|--------------------------------------------------------------------------
*/

define('NEWS_IMAGE_MAX_WIDTH', 3000);
define('NEWS_IMAGE_MAX_HEIGHT', 3000);
define('NEWS_IMAGE_MAX_SIZE', 2048);
define('NEWS_IMAGE_TOOLTIP', sprintf('максимальный размер %dx%d px не более %d kB', NEWS_IMAGE_MAX_WIDTH, NEWS_IMAGE_MAX_HEIGHT, NEWS_IMAGE_MAX_SIZE));

/*
|--------------------------------------------------------------------------
| CATALOG IMAGE CONSTS
|--------------------------------------------------------------------------
*/

define('CATALOG_IMAGE_MAX_WIDTH', 3000);
define('CATALOG_IMAGE_MAX_HEIGHT', 3000);
define('CATALOG_IMAGE_MAX_SIZE', 2048);
define('CATALOG_IMAGE_TOOLTIP', sprintf('максимальный размер %dx%d px не более %d kB', CATALOG_IMAGE_MAX_WIDTH, CATALOG_IMAGE_MAX_HEIGHT, CATALOG_IMAGE_MAX_SIZE));

/*
|--------------------------------------------------------------------------
| COMMENTS CONSTS
|--------------------------------------------------------------------------
*/

// Значение по умолчанию для комментариев (1/0 - отображать сразу/требует проверки)
define('COMMENTS_DEFAULT_VALUE', 0);
// сообщение при COMMENTS_DEFAULT_VALUE = 0
define('COMMENTS_APPROVED_MESSAGE', 'Спасибо за Ваш ответ. Комментарий появится после проверки модератором.');
// сообщение при COMMENTS_DEFAULT_VALUE = 1
define('COMMENTS_UNAPPROVED_MESSAGE', 'Спасибо за Ваш ответ.');

/*
|--------------------------------------------------------------------------
| DEPRECATED CONSTS
|--------------------------------------------------------------------------
*/

// rss site name
define('SITE_NAME', 'RG3 DEVELOPMENT');
// auth-forget password email from
define('EMAIL','info@rg3.su');
