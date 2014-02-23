<?php
/*
 * Model of cart
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Cart_item extends MY_Model_Item {

    public function __construct() {
        parent::__construct();
		$this->_info['id']				= 0;
		$this->_info['item_id']			= 0;
		$this->_info['order_id']		= 0;
		$this->_info['base_price']		= 0;
		$this->_info['discount']		= 0;
		$this->_info['date_created']	= 0;
		$this->_info['date_updated']	= 0;
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