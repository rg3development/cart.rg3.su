<?php

interface Mapper {
	public function get_object($id);
	public function save($object);
}

class MY_Model extends CI_Model {

  protected $_table;
	protected $_table_item;
	protected $_table_image;
	protected $_path_to_image;
	protected $_template;

  public function __construct() {
    parent::__construct();
  }

	public function delete($id = 0) {
		$sql_item = "delete from {$this->_table_item} where id = {$id}";
		if ($this->db->query($sql_item)) return true;
		else return false;
	}

	public function save($object) {
		return false;
	}

  protected function _create_collection($data = array(), $type = '') {
      if (sizeof($data) == 0) return array();
      $object_collection = array();
      foreach ($data as $item) {
		$object_collection[] = $this->_get_object($item, $type);
      }
      return $object_collection;
  }

}

class MY_Model_Category extends CI_Model {

	protected $_info;
	protected $_collection;

	public function __construct() {
    parent::__construct();
  }

	public function __get($name) {
		if (isset($this->_info[$name])) return $this->_info[$name];
		else return parent::__get($name);
	}

}

class MY_Model_Item extends CI_Model {

	protected $_info;

	public function __construct() {
    parent::__construct();
  }

	public function __get($name) {
		if (isset($this->_info[$name])) return $this->_info[$name];
		else return parent::__get($name);
	}

	/**
	 * method for convert cyrilic to latin
	 * @param string $str
	 * @return string
	 */
  protected function _get_url_from_title($str) {
		$tr = array(
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
			"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
			"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
			"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
			"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
			"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
			"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
			"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
			"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
			"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
            " "=>"-", ":"=>"-", ","=>"-", "."=>"-", ";"=>"-",
            "!"=>"-",
		);
		return strtr($str,$tr);
	}

}

class MY_Model_Catalog extends CI_Model
{
  const CLASS_SECTION  = 'Catalog_Section';
  const CLASS_CATEGORY = 'Catalog_Category';
  const CLASS_ITEM     = 'Catalog_Item';

  public $id;

  public function __construct( $id = 0 )
  {
    $this->id = 0;
  }

  public function link ( $cmd_type, $optional = '' )
  {
    $object_class = get_class($this);
    switch ( $cmd_type )
    {
      case 'add':
      case 'del':
      case 'edit':
      case 'items':
        switch ( $object_class )
        {
          case self::CLASS_SECTION:
            return '/admin/catalog/section/' . $cmd_type . '/' . $this->id;
            break;
          case self::CLASS_CATEGORY:
            return '/admin/catalog/category/' . $cmd_type . '/' . $this->id;
            break;
          case self::CLASS_ITEM:
            return '/admin/catalog/items/' . $cmd_type . '/' . $this->id;
            break;
        }
        break;

      case 'cat_add':
      case 'cat_list':
        switch ( $object_class )
        {
          case self::CLASS_SECTION:
            return '/admin/catalog/category/' . substr($cmd_type, 4) . '/' . $this->id;
            break;
          case self::CLASS_CATEGORY:
            return '/admin/catalog/items/' . substr($cmd_type, 4) . '/' . $this->id;
            break;
        }
        break;

      case 'item_add':
      case 'item_edit':
      case 'item_list':
        switch ( $object_class )
        {
          case self::CLASS_SECTION:
          case self::CLASS_ITEM:
            return '/admin/catalog/items/' . substr($cmd_type, 5) . '/' . $this->id;
            break;
        }
        break;

      case 'unlink':
        switch ( $object_class )
        {
          case self::CLASS_ITEM:
            return '/admin/catalog/items/' . $cmd_type . '/' . $this->id . '/' . $optional;
            break;
          case self::CLASS_CATEGORY:
            return '/admin/catalog/category/' . $cmd_type . '/' . $this->id . '/' . $optional;
            break;
        }
        break;

      case 'none':
        return '';
        break;
    }
    return '#';
  }

}
