<?php

class Comments_item extends MY_Model_Item
{

  public function __construct()
  {
    parent::__construct();
    $this->_info['id']         = 0;
    $this->_info['page_id']    = 0;
    $this->_info['page_url']   = '';
    $this->_info['name']       = '';
    $this->_info['email']      = '';
    $this->_info['message']    = '';
    $this->_info['date']       = '';
    $this->_info['is_deleted'] = 0;
    $this->_info['approved']   = 0;
  }

  public function __set($name, $value)
  {
    if (isset($this->_info[$name])) {
      if ($name == 'id' || $name == 'page_id' || $name == 'is_deleted' || $name == 'approved') {
          $this->_info[$name] = (int)$value;
      } elseif ($name == 'page_url') {
          $value = preg_replace('/&per_page=(.*)/', '', $value);
          $this->_info[$name] = trim($value);
      } else {
          $this->_info[$name] = trim($value);
      }
    } else {
          return parent::__set($name, $value);
    }
  }

}