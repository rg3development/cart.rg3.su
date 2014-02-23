<?php
/*
 * Класс работы с коллекцией изображений
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Image extends MY_Model {
    protected $_table = 'images';

    public function  __construct() {
        parent::__construct();
        $this->load->model('image_item');
    }

    public function getAllElements($id_array = array()) {
        if (sizeof($id_array) == 0) return array();
        $id_array = join(',', $id_array);
        $sql  = "SELECT * FROM {$this->_table} WHERE is_deleted = 0 AND id IN ({$id_array})";
        $data = $this->db->query($sql)->result_array();
        if ($data === false) return array();
        return $this->createCollection($data);
    }

    protected function createCollection($data = array()) {
        if (sizeof($data) == 0) return false;
        $objectCollection = array();
        foreach ($data as $date_element) {
            $tmpObject = new Image_item();
            $tmpObject->setId($date_element['id']);
            $tmpObject->setTitle($date_element['title']);
            $tmpObject->setDescription($date_element['description']);
            $tmpObject->setType($date_element['type']);
            $tmpObject->setFilename($date_element['filename']);
            $tmpObject->setSize($date_element['size']);
            $tmpObject->setWidth($date_element['width']);
            $tmpObject->setHeight($date_element['height']);
            $objectCollection[] = $tmpObject;
        }
        return $objectCollection;
    }


}

