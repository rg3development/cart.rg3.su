<?php

$system_folder      = '../system';
$application_folder = '../application';

if ( strpos($system_folder, '/') === FALSE )
{
    if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
    {
        $system_folder = realpath(dirname(__FILE__)).'/'.$system_folder;
    }
} else {
    // Swap directory separators to Unix style for consistency
    $system_folder = str_replace("\\", "/", $system_folder);
}

// The PHP file extension
define('EXT', '.php');
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// Path to the front controller (this file)
define('FCPATH', str_replace(SELF, '', __FILE__));
// Base path
define('BASEPATH', $system_folder.'/');

if (is_dir($application_folder))
{
    define('APPPATH', $application_folder.'/');
} else {
    if ($application_folder == '')
    {
        $application_folder = 'application';
    }
    define('APPPATH', BASEPATH.$application_folder.'/');
}

/*
 *---------------------------------------------------------------
 * CMS CONSTANTS INIT
 *---------------------------------------------------------------
 */
$cms_constants    = 'cms_constants.php';
$cms_constants_ex = 'cms_constants.php.example';

if ( file_exists(APPPATH.'config/'.$cms_constants) )
{
    require_once(APPPATH.'config/'.$cms_constants);
}
else
{
    require_once(APPPATH.'config/'.$cms_constants_ex);
}

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
if (defined('ENVIRONMENT'))
{
    switch (ENVIRONMENT)
    {
        case 'dev':
        case 'development':
            error_reporting(E_ALL);
        break;

        case 'prod':
        case 'production':
            error_reporting(0);
        break;

        default:
            exit('The application environment is not set correctly.');
    }
} else {
    error_reporting(E_ALL);
}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */