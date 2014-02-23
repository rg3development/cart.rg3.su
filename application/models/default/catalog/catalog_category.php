<?php

class Catalog_Category extends MY_Model_Catalog
{

  public $id;
  public $parent_category_id;
  public $parent_section_id;
  public $title;
  public $num_items;
  public $priority;

  public function __construct( $object_id = 0 )
  {
    $this->id                 = 0;
    $this->parent_category_id = 0;
    $this->parent_section_id  = 0;
    $this->title              = '';
    $this->num_items          = 0;
    $this->priority           = 0;
  }

  public function num_products ()
  {
    $catalog_mapper = new Catalog_mapper();
    return count($catalog_mapper->get_category_item_list($this->id));
  }

  public function catalog_url ()
  {
      $catalog_mapper = new Catalog_Mapper();
      $page_mapper    = new Page_Mapper();
      $section = $catalog_mapper->get_object($this->parent_section_id, 'section');
      return $page_mapper->get_page($section->parent_page_id)->url;
  }

  public function category_link ()
  {
    return $this->catalog_url() . '?cat=' . $this->id;
  }

}