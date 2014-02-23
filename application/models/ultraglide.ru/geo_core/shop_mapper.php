<?php

class Shop_mapper extends MY_Model implements Mapper
{

    public function  __construct ()
    {
        parent::__construct();

        $this->_template['index'] = 'geo_shop/index';

        $this->_table_town = 'shop_town';
        $this->_table_shop = 'shop_item';

        $this->load->model('geo_core/shop_town');
        $this->load->model('geo_core/shop_item');
    }

    public function get_object ( $id ) {}

    public function get_full_town_list ( $order_by = 'priority', $order_type = 'ASC' )
    {
        return $this->db->select('*')
            ->from($this->_table_town)
            ->where('is_deleted', 0)
            ->order_by($order_by, $order_type)
            ->get()
            ->result();
    }

    public function get_town_list ( $show = 1, $order_by = 'priority', $order_type = 'ASC' )
    {
        return $this->db->select('*')
            ->from($this->_table_town)
            ->where('show', $show)
            ->where('is_deleted', 0)
            ->order_by($order_by, $order_type)
            ->get()
            ->result();
    }

    public function get_town_item ( $id = 0 )
    {
        return $this->db->select('*')
            ->from($this->_table_town)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function get_first_town ( $order_by = 'priority', $order_type = 'ASC' )
    {
        return $this->db->select('*')
            ->from($this->_table_town)
            ->where('show', 1)
            ->where('is_deleted', 0)
            ->order_by($order_by, $order_type)
            ->limit(1)
            ->get()
            ->row();
    }

    public function get_full_shop_list ( $town_id = 0, $order_by = 'priority', $order_type = 'ASC' )
    {
        return $this->db->select('*')
            ->from($this->_table_shop)
            ->where('is_deleted', 0)
            ->where('town_id', $town_id)
            ->order_by($order_by, $order_type)
            ->get()
            ->result();
    }

    public function get_shops_info ( $order_by = 'priority', $order_type = 'ASC' )
    {
        return $this->db
            ->select('
                shop_town.title town,
                shop_item.id shop_id,
                shop_item.title shop,
                shop_item.address address
            ')
            ->from('shop_item')
            ->join('shop_town', 'shop_item.town_id = shop_town.id')
            ->where('shop_item.is_deleted', 0)
            ->where('shop_town.is_deleted', 0)
            ->get()
            ->result();
    }

    public function get_shop_item ( $id = 0 )
    {
        return $this->db->select('*')
            ->from($this->_table_shop)
            ->where('id', $id)
            ->get()
            ->row();
    }

    public function save ( $object, $type = '' )
    {
        $data = array();
        switch ( $type )
        {
            case 'town':
                $data['title']     = $object->title;
                $data['priority']  = $object->priority;
                $data['show']      = $object->show;
                $data['longitude'] = $object->longitude;
                $data['latitude']  = $object->latitude;
                if ( $object->id )
                {
                    $this->db->where('id', $object->id);
                    $result = $this->db->update($this->_table_town, $data);
                }
                else
                {
                    $this->db->insert($this->_table_town, $data);
                    $result = $this->db->insert_id();
                }
                break;

            case 'shop':
                $data['town_id']   = $object->town_id;
                $data['title']     = $object->title;
                $data['address']   = $object->address;
                $data['phones']    = $object->phones;
                $data['work_time'] = $object->work_time;
                $data['priority']  = $object->priority;
                $data['longitude'] = $object->longitude;
                $data['latitude']  = $object->latitude;
                if ( $object->id )
                {
                    $this->db->where('id', $object->id);
                    $result = $this->db->update($this->_table_shop, $data);
                }
                else
                {
                    $this->db->insert($this->_table_shop, $data);
                    $result = $this->db->insert_id();
                }
                break;

            default:
                $result = FALSE;
                break;
        }
        return $result;
    }

    public function delete ( $obj_id = 0, $type = '' )
    {
        $data = array();
        $data['is_deleted'] = 1;
        $this->db->where('id', $obj_id);
        switch ( $type )
        {
            case 'town':
                $result = $this->db->update($this->_table_town, $data);
                break;

            case 'shop':
                $result = $this->db->update($this->_table_shop, $data);
                break;

            default:
                $result = FALSE;
                break;
        }
        return $result;
    }

    public function get_page_content ( $page_id = 0 )
    {
        $template_data = array();
        $shop_list     = array();

        $this->load->model('geo_core/geo_core');
        $city = $this->geo_core->get_value();

        if ( $user_city = $this->input->post('city') )
        {
            $city_name = mb_strtoupper($user_city);
        } else {
            $city_name = mb_strtoupper($city['city']);
        }

        $town_list = $this->get_town_list();

        $town_array  = $this->get_assoc_town_array($town_list);
        $town_info   = $this->get_select_option_town($town_list, $city_name);
        $town_select = $town_info['select'];

        if ( $town_info['city_id'] )
        {
            $object = $this->shop_mapper->get_town_item($town_info['city_id']);
        } else {
            $object    = $this->shop_mapper->get_first_town();
            $shop_list = $this->get_full_shop_list($object->id);
        }

        if ( array_key_exists($city_name, $town_array) )
        {
            $shop_list = $this->get_full_shop_list($town_array[$city_name]);
        }
        $template_data['cur_city']    = $object;
        $template_data['shop_list']   = $shop_list;
        $template_data['town_select'] = $town_select;
        return $this->load->site_view($this->_template['index'], $template_data, TRUE);
    }

    private function get_assoc_town_array ( $town_list )
    {
        $result = array();
        foreach ( $town_list as $key => $town )
        {
            $result[mb_strtoupper($town->title)] = $town->id;
        }
        return $result;
    }

    private function get_select_option_town ( $town_list, $cur_city )
    {
        $select  = array();
        $city_id = 0;
        foreach ( $town_list as $key => $town )
        {
            if ( mb_strtoupper($town->title) == $cur_city )
            {
                $select[$town->title] = 1;
                $city_id = $town->id;
            } else {
                $select[$town->title] = 0;
            }
        }
        $result['select']  = $select;
        $result['city_id'] = $city_id;
        return $result;
    }

}