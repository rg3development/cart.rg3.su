<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation  {
	function __construct() {
		parent::__construct();
	}
	function set_errors($fields) {
		if (is_array($fields) and count($fields)) {
			foreach($fields as $key => $val) {
				$this->_field_data[$key]['error'] = $val;
				$this->_error_array[$key] = $val;
			}
		}
    }
}
