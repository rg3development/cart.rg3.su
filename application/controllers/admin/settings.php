<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

    protected $_table;
    protected $_module_title;
    protected $_templates;

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->_table              = 'settings';
        $this->_module_title       = 'настройка сайта';
        $this->_templates['index'] = 'settings/index';

        $this->template_data['module_title']  = $this->_module_title;
    }

    public function index() {
        $settings = $this->db->query("select * from {$this->_table}")->result_array();
        if ( ! empty($_POST) )
        {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'поле "%s" незаполнено');
            $this->form_validation->set_rules('SITE_TITLE', '<b>название</b>','trim|required');
            $this->form_validation->set_rules('SITE_COUNTERS', '<b>счетчики</b>','trim');
            if ($this->form_validation->run() != FALSE)
            {
                if ( !empty($_FILES) && $_FILES['SITE_LOGO']['error'] == 0 )
                {
                    $image = new Image_item();
                    $image->doUpload(800, 800, 'SITE_LOGO', 'gif|jpg|png', 2048, 'settings');
                    $image_id = $image->Save();
                    $settings[5]['value'] = $image_id;
                }
                $settings[0]['value'] = $this->input->post('SITE_TITLE');
                $settings[1]['value'] = $this->input->post('SITE_DESCRIPTION');
                $settings[2]['value'] = $this->input->post('SITE_KEYWORDS');
                $settings[3]['value'] = $this->input->post('EMAIL');
                $settings[4]['value'] = $this->input->post('MY_EMAIL');
                $settings[6]['value'] = $this->input->post('SITE_COUNTERS');
                foreach ($settings as $setting)
                {
                    $sql = "update {$this->_table} set value = {$this->db->escape($setting['value'])} where name = {$this->db->escape($setting['name'])}";
                    $this->db->query($sql);
                }
            }
        }
        if ( $settings[5]['value'] )
        {
            $small_image = new Image_item($settings[5]['value']);
            $settings[5]['logo'] = IMAGESRC.'settings/'.$small_image->getFilename();
        }

        $this->template_data['settings'] = $settings;

        $this->load->admin_view($this->_templates['index'], $this->template_data);

    }

}