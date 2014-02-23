<?php

class Shop_town extends MY_Model_Category
{

    public function  __construct()
    {
        parent::__construct();
        $this->_info['id']         = 0;
        $this->_info['title']      = '';
        $this->_info['priority']   = 0;
        $this->_info['show']       = 0;
        $this->_info['longitude']  = '';
        $this->_info['latitude']   = '';
        $this->_info['is_deleted'] = 0;
    }

    public function __set($name, $value)
    {
        if ( isset($this->_info[$name]) )
        {
            switch ( $name )
            {
                case 'title':
                case 'longitude':
                case 'latitude':
                    $this->_info[$name] = trim($value);
                    break;

                default:
                    $this->_info[$name] = (int) $value;
                    break;
            }
        }
        else
        {
            return parent::__set($name, $value);
        }
    }

}