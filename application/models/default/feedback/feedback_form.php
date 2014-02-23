<?php

class Feedback_form extends MY_Model_Item {

    public function __construct() {
        parent::__construct();
        $this->_info['id']            = 0;
        $this->_info['parent_id']     = 0;
        $this->_info['title']         = '';

        $this->_info['email_subject'] = '';
        $this->_info['email_to']      = '';
        $this->_info['email_from']    = '';
        $this->_info['email_name']    = '';
    }

    public function __set($name, $value) {
        if (isset($this->_info[$name])) {
            if ($name == 'title' || $name == 'email_subject' || $name == 'email_to' || $name == 'email_from' || $name == 'email_name' ) $this->_info[$name] = trim($value);
            else $this->_info[$name] = (int)$value;
        } else {
            return parent::__set($name, $value);
        }
    }
}