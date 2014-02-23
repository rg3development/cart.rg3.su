<?php
/*
 * Model of gallery category
 *
 * @author rav <arudyuk@rg3.su>
 * @version 1.0
 */

class Gallery_category extends MY_Model_Category {
	public function  __construct() {
		parent::__construct();
		$this->_info['id']				= 0;
		$this->_info['parent_id']		= 0;
		$this->_info['title']			= '';
		$this->_info['show_title']		= 0;
		$this->_info['script_type']		= 0;
		$this->_info['count_per_page']	= 0;
		$this->_info['resize_width']    = 0;
        $this->_info['resize_height']   = 0;
	}

	public function __set($name, $value) {
		if (isset($this->_info[$name])) {
			$this->_info[$name] = $name == 'title' ? trim($value) : (int)$value;
		} else {
			return parent::__set($name, $value);
		}
	}
}
