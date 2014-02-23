<?php
/*
 * Model of banner category
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Banner_category extends MY_Model_Category {
    public function  __construct() {
        parent::__construct();
        $this->_info['id']              = 0;
        $this->_info['parent_id']       = 0;
        $this->_info['title']           = '';
        $this->_info['show_title']      = '';
        $this->_info['resize_width']    = '';
        $this->_info['resize_height']   = '';
        $this->_info['count_per_page']  = 0;
    }

    public function __set($name, $value) {
        if (isset($this->_info[$name])) {
            $this->_info[$name] = ( $name == 'title' ? trim($value) : (int)$value );
        } else {
            return parent::__set($name, $value);
        }
    }
}
