<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Base Site URL
$config['base_url']             = 'http://'.$_SERVER['HTTP_HOST'].'/';

// Index File
$config['index_page']           = '';

// URI PROTOCOL
$config['uri_protocol']         = php_sapi_name() == 'cli' ? 'AUTO' : 'PATH_INFO';

// URL suffix
$config['url_suffix']           = '';

// Default Language
$config['language']             = 'english';

// Default Character Set
$config['charset']              = 'UTF-8';

// Enable/Disable System Hooks
$config['enable_hooks']         = TRUE;

// Class Extension Prefix
$config['subclass_prefix']      = 'MY_';

// Allowed URL Characters
$config['permitted_uri_chars']  = 'a-z 0-9~%.:_\-';

// Enable Query Strings
$config['allow_get_array']      = TRUE;
$config['enable_query_strings'] = TRUE;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd'; // experimental not currently in use

// Error Logging Threshold
$config['log_threshold']        = 1;

// Error Logging Directory Path
$config['log_path']             = '';

// Date Format for Logs
$config['log_date_format']      = 'd.m.Y H:i:s';

// Cache Directory Path
$config['cache_path']           = '';

// Encryption Key
$config['encryption_key']       = '4a7b3b4c55334c5c3a4c37273c2c516b5b367d6839267456685e263f58';

// Session Variables
$config['sess_cookie_name']     = 'ci_session';
$config['sess_expiration']      = 604800;
$config['sess_encrypt_cookie']  = TRUE;
$config['sess_use_database']    = TRUE;
$config['sess_table_name']      = 'user_sessions';
$config['sess_match_ip']        = FALSE;
$config['sess_match_useragent'] = TRUE;
$config['sess_time_to_update']  = $config['sess_expiration'];

// Cookie Related Variables
$config['cookie_prefix']        = '';
$config['cookie_domain']        = '';
$config['cookie_path']          = '/';

// Global XSS Filtering
$config['global_xss_filtering'] = FALSE;

// Output Compression
$config['compress_output']      = FALSE;

// Master Time Reference
$config['time_reference']       = 'local';

// Rewrite PHP Short Tags
$config['rewrite_short_tags']   = FALSE;

// Reverse Proxy IPs
$config['proxy_ips']            = '';