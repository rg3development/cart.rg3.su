<?php
/*
 * Model of response item
 *
 * @author rav <arudyuk@rg3.su> 
 * @copyright RG3 Development
 * @version 1.0
 */

class Response_item extends MY_Model_Item {

    public function __construct() {
        parent::__construct();
        $this->_info['id']               = 0;
        $this->_info['parent_id']        = 0;
        $this->_info['title']            = '';
        $this->_info['author']           = '';
        $this->_info['description']      = '';
        $this->_info['spec_link']        = '';
        $this->_info['is_spec_link']     = 0;
        $this->_info['link_new_window']  = 0;
        $this->_info['user_day']         = 0;
        $this->_info['user_month']       = 0;
        $this->_info['user_year']        = 0;
        $this->_info['user_hour']        = 0;
        $this->_info['user_min']         = 0;
        $this->_info['created']          = time();
    }

    public function __set($name, $value) {
        if (isset($this->_info[$name])) {
            if ($name == 'title' || $name == 'description' || $name == 'spec_link' || $name == 'author') $this->_info[$name] = trim($value);
            else $this->_info[$name] = (int)$value;
        } else {
            return parent::__set($name, $value);
        }
    }
}