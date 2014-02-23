<?php
/*
 * Model of response category
 *
 * @author rav <arudyuk@rg3.su> 
 * @copyright RG3 Development
 * @version 1.0
 */

class Response_category extends MY_Model_Category {

    public function  __construct() {
        parent::__construct();
        $this->_info['id']                = 0;
        $this->_info['parent_id']         = 0;
        $this->_info['title']             = '';
        $this->_info['show_title']        = 0;
        $this->_info['count_per_page']    = 0;
    }

    public function __set($name, $value) {
        if (isset($this->_info[$name])) {
            $this->_info[$name] = $name == 'title' ? trim($value) : (int)$value;
        } else {
            return parent::__set($name, $value);
        }
    }

    public function __get($name) {
        if (isset($this->_info[$name])) return $this->_info[$name];
        else return parent::__get($name);
    }
}
