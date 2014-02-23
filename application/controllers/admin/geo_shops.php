<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Geo_shops extends Admin_Controller
{

    protected $_module_title;
    protected $_templates;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('geo_core/shop_mapper');

        $this->_module_title = 'менеджер магазинов';

        $this->_templates['index']      = 'geo_shop/index';
        $this->_templates['add']        = 'geo_shop/add';
        $this->_templates['edit']       = 'geo_shop/edit';
        $this->_templates['shop_index'] = 'geo_shop/shop_index';
        $this->_templates['shop_add']   = 'geo_shop/shop_add';
        $this->_templates['shop_edit']  = 'geo_shop/shop_edit';

        $this->template_data['module_title']  = $this->_module_title;
    }

    public function index ()
    {
        $shop_list = $this->shop_mapper->get_full_town_list();
        $this->template_data['shop_list'] = $shop_list;
        $this->load->admin_view($this->_templates['index'], $this->template_data);
    }

    public function add ()
    {
        if ( ! empty($_POST) )
        {
            $this->form_validation->set_rules('title', 'название', 'trim|required');
            $this->form_validation->set_rules('priority', 'порядок', 'trim|required');
            // $this->form_validation->set_rules('latitude', 'широта', 'trim|required');
            // $this->form_validation->set_rules('longitude', 'долгота', 'trim|required');
            $this->form_validation->set_message('required', 'поле "%s" обязательно для заполнения');
            if ( $this->form_validation->run() )
            {
                $object = new Shop_town();
                $object->title     = $this->input->post('title');
                $object->priority  = $this->input->post('priority');
                $object->show      = ($this->input->post('show') == 'on') ? 1 : 0;
                $object->longitude = $this->input->post('longitude');
                $object->latitude  = $this->input->post('latitude');
                $this->shop_mapper->save($object, 'town');
                redirect(base_url('admin/geo_shops'));
            }
        }
        $this->load->admin_view($this->_templates['add'], $this->template_data);
    }

    public function edit ( $id = 0 )
    {
        $object = $this->shop_mapper->get_town_item($id);
        if ( ! empty($_POST) )
        {
            $this->form_validation->set_rules('title', 'название', 'trim|required');
            $this->form_validation->set_rules('priority', 'порядок', 'trim|required');
            // $this->form_validation->set_rules('latitude', 'широта', 'trim|required');
            // $this->form_validation->set_rules('longitude', 'долгота', 'trim|required');
            $this->form_validation->set_message('required', 'поле "%s" обязательно для заполнения');
            if ( $this->form_validation->run() )
            {
                $object->title     = $this->input->post('title');
                $object->priority  = $this->input->post('priority');
                $object->show      = ($this->input->post('show') == 'on') ? 1 : 0;
                $object->longitude = $this->input->post('longitude');
                $object->latitude  = $this->input->post('latitude');
                $this->shop_mapper->save($object, 'town');
                redirect(base_url('admin/geo_shops'));
            }
        }
        $this->template_data['town_item'] = $object;
        $this->load->admin_view($this->_templates['edit'], $this->template_data);
    }

    public function delete ( $town_id )
    {
        $this->shop_mapper->delete($town_id, 'town');
        redirect(base_url('admin/geo_shops'));
    }

    public function show ( $parent_id )
    {
        $shop_list = $this->shop_mapper->get_full_shop_list($parent_id);
        $this->template_data['parent_id'] = $parent_id;
        $this->template_data['shop_list'] = $shop_list;
        $this->load->admin_view($this->_templates['shop_index'], $this->template_data);
    }

    public function add_shop ( $parent_id )
    {
        if ( ! empty($_POST) )
        {
            $this->form_validation->set_rules('title', 'название', 'trim|required');
            $this->form_validation->set_rules('priority', 'порядок', 'trim|required');
            $this->form_validation->set_rules('address', 'адрес', 'trim|required');
            // $this->form_validation->set_rules('latitude', 'широта', 'trim|required');
            // $this->form_validation->set_rules('longitude', 'долгота', 'trim|required');
            $this->form_validation->set_message('required', 'поле "%s" обязательно для заполнения');
            if ( $this->form_validation->run() )
            {
                $object = new Shop_item();
                $object->town_id   = $this->input->post('town_id');
                $object->title     = $this->input->post('title');
                $object->address   = $this->input->post('address');
                $object->phones    = $this->input->post('phones');
                $object->work_time = $this->input->post('work_time');
                $object->priority  = $this->input->post('priority');
                $object->longitude = $this->input->post('longitude');
                $object->latitude  = $this->input->post('latitude');
                $this->shop_mapper->save($object, 'shop');
                redirect(base_url('admin/geo_shops/show/'.$parent_id));
            }
        }
        $this->template_data['parent_id'] = $parent_id;
        $this->load->admin_view($this->_templates['shop_add'], $this->template_data);
    }

    public function edit_shop ( $id = 0, $parent_id = 0 )
    {
        $object = $this->shop_mapper->get_shop_item($id);
        if ( ! empty($_POST) )
        {
            $this->form_validation->set_rules('title', 'название', 'trim|required');
            $this->form_validation->set_rules('priority', 'порядок', 'trim|required');
            $this->form_validation->set_rules('address', 'адрес', 'trim|required');
            // $this->form_validation->set_rules('latitude', 'широта', 'trim|required');
            // $this->form_validation->set_rules('longitude', 'долгота', 'trim|required');
            $this->form_validation->set_message('required', 'поле "%s" обязательно для заполнения');
            if ( $this->form_validation->run() )
            {
                $object->town_id   = $this->input->post('town_id');
                $object->title     = $this->input->post('title');
                $object->address   = $this->input->post('address');
                $object->phones    = $this->input->post('phones');
                $object->work_time = $this->input->post('work_time');
                $object->priority  = $this->input->post('priority');
                $object->longitude = $this->input->post('longitude');
                $object->latitude  = $this->input->post('latitude');
                $this->shop_mapper->save($object, 'shop');
                redirect(base_url('admin/geo_shops/show/'.$parent_id));
            }
        }
        $this->template_data['shop_item'] = $object;
        $this->template_data['parent_id'] = $parent_id;
        $this->load->admin_view($this->_templates['shop_edit'], $this->template_data);
    }

    public function delete_shop ( $shop_id, $parent_id )
    {
        $this->shop_mapper->delete($shop_id, 'shop');
        redirect(base_url('admin/geo_shops/show/'.$parent_id));
    }

    public function coordinates ( $type = '' )
    {
        exit('disable');

        switch ( $type )
        {
            case 'shop':
                $shop_list = $this->shop_mapper->get_shops_info();
                foreach ( $shop_list as $key => $shop )
                {
                    $object = $this->shop_mapper->get_shop_item($shop->shop_id);
                    if ( $object->longitude && $object->latitude )
                    {
                        print("coord: ok \n");
                    } else {
                        // prepare query string
                        $address      = $shop->town . ' ' . $shop->address;
                        $query_string = $this->prepare_geo_string($address);
                        print('search: '.$query_string."\n");

                        // get coords
                        $geolocation = $this->geolocation($query_string);
                        print('Result: ');
                        print_r($geolocation);
                        print("\n\n");

                        // update shop object
                        $object->longitude = $geolocation['xlong'];
                        $object->latitude  = $geolocation['xlat'];
                        $this->shop_mapper->save($object, 'shop');
                    }
                }
                break;

            case 'town':
                $town_list = $this->shop_mapper->get_full_town_list();
                foreach ( $town_list as $key => $town )
                {
                    $object = $this->shop_mapper->get_town_item($town->id);
                    if ( $object->longitude && $object->latitude )
                    {
                        print("coord: ok \n");
                    } else {
                        // prepare string
                        $query_string = $this->prepare_geo_string($town->title);
                        print('search: '.$query_string."\n");

                        // get coords
                        $geolocation = $this->geolocation($query_string);
                        print('Result: ');
                        print_r($geolocation);
                        print("\n\n");

                        // update town object
                        $object->longitude = $geolocation['xlong'];
                        $object->latitude  = $geolocation['xlat'];
                        $this->shop_mapper->save($object, 'town');
                    }
                }
                break;
        }
        echo 'complete';
    }

    public function check_shop ()
    {
        $address = $this->input->post('address');
        $town_id = $this->input->post('town_id');

        $object  = $this->shop_mapper->get_town_item($town_id);
        $address = $object->title . ' ' . $address;

        $query_string = $this->prepare_geo_string($address);
        $result = $this->geolocation($query_string);
        $result['query'] = $address;
        print(json_encode($result));
    }

    private function geolocation ( $query_string = '' )
    {
        sleep(2);

        $url       = "http://maps.google.com/maps/api/geocode/json?address=$query_string&sensor=false";
        $json_data = file_get_contents($url);
        $data      = json_decode($json_data);
        $res = array();

        $xlat  = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $xlong = $data->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $res['xlat']  = $xlat;
        $res['xlong'] = $xlong;

        return $res;
    }

    private function prepare_geo_string ( $query_string = '' )
    {
        return str_replace(' ', '+', $query_string);
    }

}