<?php
/*
 * Model of text
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Text_item extends MY_Model_Item {

    public function __construct() {
        parent::__construct();
		$this->_info['id']				= 0;
		$this->_info['parent_id']		= 0;
		$this->_info['show_title']		= 0;
		$this->_info['title']			= '';
		$this->_info['description']		= '';
    }

	public function __set($name, $value) {
		if (isset($this->_info[$name])) {
			if ($name == 'id' || $name == 'parent_id') {
				$this->_info[$name] = (int)$value;
			} else {
				$this->_info[$name] = trim($value);
			}
		} else {
			return parent::__set($name, $value);
		}
	}
}