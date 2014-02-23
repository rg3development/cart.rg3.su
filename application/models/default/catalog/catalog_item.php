<?php

define('IN_STOCK_MSG', 'Товар в наличии');
define('IN_STOCK_MSG_NOT', 'Нет в наличии');

class Catalog_Item extends MY_Model_Catalog
{

    public $id;
    public $title;
    public $description;
    public $article;
    public $price;
    public $section_id;
    public $in_stock;
    public $is_bestseller;
    public $is_sale;
    public $priority;

    public function __construct( $object_id = 0 )
    {
        $this->id            = 0;
        $this->title         = '';
        $this->description   = '';
        $this->article       = '';
        $this->price         = 0;
        $this->section_id    = 0;
        $this->in_stock      = 0;
        $this->is_bestseller = 0;
        $this->is_sale       = 0;
        $this->priority      = 0;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function num_categories ()
    {
        $catalog_mapper = new Catalog_Mapper();
        return count($catalog_mapper->get_item_category_list($this->id));
    }

    // получить ссылку на 1е (основное) изображение товара
    public function image ()
    {
        $image = NULL;
        if ( $this->id )
        {
            $catalog_mapper = new Catalog_Mapper();
            $image = $catalog_mapper->get_main_image($this->id);
        }
        return $this->img_src($image);
    }

    public function thumb ()
    {
        $image = NULL;
        if ( $this->id )
        {
            $catalog_mapper = new Catalog_Mapper();
            $image = $catalog_mapper->get_main_image($this->id);
        }
        return $this->img_src_thumb($image);
    }

    // получить массив изображений товара
    public function images ()
    {
        $result = array();
        if ( $this->id )
        {
            $catalog_mapper = new Catalog_Mapper();
            $result = $catalog_mapper->get_images($this->id);
        }
        return $result;
    }

    // генератор ссылок на изображение
    public function img_src ( $img_obj, $full_url = FALSE )
    {
        $image_src = '/img/admin/noimage.svg';
        if ( $img_obj && $img_obj->image_id )
        {
            $image = new Image_item($img_obj->image_id);
            if ( $full_url )
            {
                $image_src = base_url('/upload/images/catalog/' . $image->getFilename());
            } else {
                $image_src = '/upload/images/catalog/' . $image->getFilename();
            }
        }
        return $image_src;
    }

    // генератор ссылок на thumbnail
    public function img_src_thumb ( $img_obj, $full_url = FALSE )
    {
        $image_src = '/img/admin/noimage.svg';
        if ( $img_obj && $img_obj->image_id )
        {
            $image = new Image_item($img_obj->image_id);
            if ( $full_url )
            {
                $image_src = base_url('/upload/images/catalog/' . $image->getFilenameThumb());
            } else {
                $image_src = '/upload/images/catalog/' . $image->getFilenameThumb();
            }
        }
        return $image_src;
    }

    public function currency ()
    {
        $catalog_mapper = new Catalog_Mapper();
        return $catalog_mapper->get_section_currency($this->section_id);
    }

    // получить строковое представление цены
    public function price()
    {
        return $this->cart->format_number($this->price) . ' ' . $this->currency();
    }

    public function catalog_url ()
    {
        $catalog_mapper = new Catalog_Mapper();
        $page_mapper    = new Page_Mapper();
        $section = $catalog_mapper->get_object($this->section_id, 'section');
        return $page_mapper->get_page($section->parent_page_id)->url;
    }

    // получить ссылку на подробное описание товара
    public function details ()
    {
        $catalog_url = $this->catalog_url();
        return base_url($catalog_url . '?item=' . $this->id);
    }

    // получить полный url товара
    public function url ( $params = '' )
    {
        $catalog_url = $this->catalog_url();
        return base_url($catalog_url . '?item=' . $this->id . $params);
    }

    public function add_to_cart ()
    {
        $form = '
        <form id="cart_add" method="post">
        <input type="hidden" name="type" value="add" />
        <input type="hidden" name="qty" value="1" />
        <input type="hidden" name="item_id" value="' . $this->id . '" />
        </form>
        ';
        print($form);
    }

    public function buy ()
    {
        $form = '
        <form name="cart_add" id="cart_add_' . $this->id . '" method="post" action="/catalog">
        <input type="hidden" name="type" value="add" />
        <input type="hidden" name="qty" value="1" />
        <input type="hidden" name="item_id" value="' . $this->id . '" />
        </form>
        ';
        print($form);
    }

    public function user_fields ()
    {
        $result = array();
        if ( $this->id )
        {
            $catalog_mapper = new Catalog_Mapper();
            $result = $catalog_mapper->get_uf_values($this->section_id, $this->id);
        }
        return $result;
    }

    public function category ()
    {
        $catalog_mapper = new Catalog_Mapper();
        $categories = $catalog_mapper->get_item_category_list($this->id);
        if ( !empty($categories) )
        {
            return $categories[0];
        }
        return array();
    }

    // Возвращает полное или короткое описание товара, если указана длина сообщения
    // Короткое описание выводится без заданного форматирования
    public function description ( $length = NULL )
    {
        if ( $length )
        {
            return mb_substr(strip_tags($this->description), 0, $length);
        }
        return $this->description;
    }

    // Возвращает строковое или булево значение поля "Товар в наличии"
    public function in_stock ( $bool_result = FALSE )
    {
        if ( $bool_result )
        {
            return (bool) $this->in_stock;
        }
        return ( $this->in_stock ) ? IN_STOCK_MSG : IN_STOCK_MSG_NOT ;
    }

    public function clean_string ( $string )
    {
        $patterns = array();
        $patterns[0] = '/&nbsp;/';
        $patterns[1] = '/&Prime;/';
        $patterns[2] = '/&mdash;/';
        $patterns[3] = '/&laquo;/';
        $patterns[4] = '/&raquo;/';
        $patterns[5] = '/&times;/';
        $patterns[6] = '/&rsquo;/'; // ’
        $patterns[7] = '/&ldquo;/'; // “
        $patterns[8] = '/&rdquo;/'; // ”

        $replacements = array();
        $replacements[0] = '';
        $replacements[1] = '';
        $replacements[2] = '';
        $replacements[3] = '&quot;';
        $replacements[4] = '&quot;';
        $replacements[5] = '';
        $replacements[6] = '&apos;';
        $replacements[7] = '&quot;';
        $replacements[8] = '&quot;';

        return preg_replace($patterns, $replacements, $string);
    }

}