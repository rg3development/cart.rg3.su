<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = DB_HOSTNAME;
$db['default']['username'] = DB_USERNAME;
$db['default']['password'] = DB_PASSWORD;
$db['default']['database'] = DB_DATABASE;
$db['default']['dbdriver'] = DB_DBDRIVER;
$db['default']['dbprefix'] = DB_DBPREFIX;
$db['default']['pconnect'] = DB_PCONNECT;
$db['default']['db_debug'] = DB_DB_DEBUG;
$db['default']['cache_on'] = DB_CACHE_ON;
$db['default']['cachedir'] = DB_CACHEDIR;
$db['default']['char_set'] = DB_CHAR_SET;
$db['default']['dbcollat'] = DB_DBCOLLAT;
$db['default']['swap_pre'] = DB_SWAP_PRE;
$db['default']['autoinit'] = DB_AUTOINIT;
$db['default']['stricton'] = DB_STRICTON;