<?php
/*
 * Model of page
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Page_item extends MY_Model_Item {
	protected $_info;
	protected $_modules;

    public function __construct() {
        parent::__construct();
		$this->_info['id']           = 0;
		$this->_info['parent_id']    = 0;
		$this->_info['url']          = '';
		$this->_info['image_link']   = '';
		$this->_info['image_id1']    = 0;
		$this->_info['image_id2']    = 0;
		$this->_info['meta']         = '';
		$this->_info['description']  = '';
		$this->_info['keywords']     = '';
		$this->_info['level']        = 0;
		$this->_info['priority']     = 0;
		$this->_info['title']        = '';
		$this->_info['show_title']   = 0;
		$this->_info['show']         = 0;
		$this->_info['template']     = 'inner';
		$this->_info['image_bottom'] = 0;
		$this->_info['alias']        = '';
		$this->_info['show_alias']   = 0;

		// данные из настройки сайта
		$this->_info['logo']             = '';
		$this->_info['site_title']       = '';
		$this->_info['site_description'] = '';
		$this->_info['site_keywords']    = '';
    }

	public function __set($name, $value) {
		if (isset($this->_info[$name])) {
			if (
					$name == 'url'
				|| $name == 'meta'
				|| $name == 'title'
				|| $name == 'image_link'
				|| $name == 'description'
				|| $name == 'keywords'
				|| $name == 'template'
				|| $name == 'logo'
				|| $name == 'site_title'
				|| $name == 'site_keywords'
				|| $name == 'site_description'
				|| $name == 'alias'
			) {
				$this->_info[$name] = trim($value);
			} else {
				$this->_info[$name] = (int)$value;
			}
		} else {
			return parent::__set($name, $value);
		}
	}

	public function __get($name) {
		if (isset($this->_info[$name])) return $this->_info[$name];
		else return parent::__get($name);
	}
}
