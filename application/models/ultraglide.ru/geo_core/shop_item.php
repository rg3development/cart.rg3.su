<?php

class Shop_item extends MY_Model_Item
{

    public function __construct($id = 0)
    {
        parent::__construct();
        $this->_info['id']         = 0;
        $this->_info['town_id']    = 0;
        $this->_info['title']      = '';
        $this->_info['address']    = '';
        $this->_info['phones']     = '';
        $this->_info['work_time']  = '';
        $this->_info['priority']   = 0;
        $this->_info['is_deleted'] = 0;
        $this->_info['longitude']  = '';
        $this->_info['latitude']   = '';
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
                case 'address':
                case 'phones':
                case 'work_time':
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