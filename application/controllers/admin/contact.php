<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ContactBlock extends Admin_Controller {

    protected $_module_title;
	protected $_text_id;
    protected $_template;

    public function __construct() {
        parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('text_item', 'Text_item');

		$this->_module_title	= 'контактный блок';
		$this->_text_id			= 14;
		$this->_template		= 'contactblock/edit';
		$this->scripts[]		= base_url().'js/tiny_mce/tiny_mce.js';
    }


	public function index() {
		$editText           = new Text_item($this->_text_id);

        $data = array();
        $data['id']         = $editText->getId();
        $data['text']		= $editText->getDescription1();

        if (!empty($_POST)) {
            $this->form_validation->set_error_delimiters('', '<br/>');
            $this->form_validation->set_message('required', 'Поле "%s" незаполнено');
            $this->form_validation->set_rules('text', '<b>текст</b>','trim|required');
            if ($this->form_validation->run() != FALSE) {
                $editText->setDescription1($this->input->post('text'));
                $editText->Save();
                redirect(base_url().'admin/contactblock');
            }
            $this->load->admin_view($this->_template, array('data' => $data));
        } else {
            $this->load->admin_view($this->_template, array('data' => $data));
        }
	}
}
