<?php

/*
 * Класс работы с изображением
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Image_item extends MY_Model_Item {

    protected $_table = 'images';
    protected $_id = 0;
    protected $_title = '';
    protected $_description = '';
    protected $_filename = '';
    protected $_size = 0;
    protected $_width = 0;
    protected $_height = 0;
    protected $_path = '';
    protected $_thumb_marker = '';
    /*
     * types: jpeg, png и другие
     */
    protected $_type = '';

    public function __construct($id = 0) {
        parent::__construct();
        $this->_id = (int) $id;
        if ($this->_id > 0) {
            $res = $this->db->get_where($this->_table, array('id' => $this->_id), 1, 0)->row();
            if ($res != false) {
                $this->_id = $res->id;
                $this->_filename = $res->filename;
                $this->_size = $res->size;
                $this->_width = $res->width;
                $this->_height = $res->height;
                $this->_type = $res->type;
            }
        }
    }

    public function setId($id = 0) {
        $this->_id = (int) $id;
        return true;
    }

    public function getId() {
        return $this->_id;
    }

    public function setFilename($filename) {
        $this->_filename = $filename;
    }

    public function getFilename() {
        return $this->_filename;
    }

    public function getFilenameThumb($thumb = '_thumb') {
        return substr($this->_filename, 0, -4) . $thumb . substr($this->_filename, -4);
    }

    public function setFlyImageName ( $width = 0, $height = 0 )
    {
        return substr($this->_filename, 0, -4) . "-{$width}x{$height}" . substr($this->_filename, -4);
    }

    public function setSize($size) {
        $this->_size = (float) $size;
    }

    public function getSize() {
        return $this->_size;
    }

    public function setWidth($width) {
        $this->_width = (int) $width;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function setHeight($height) {
        $this->_height = (int) $height;
    }

    public function getHeight() {
        return $this->_height;
    }

    public function setType($type) {
        $this->_type = $type;
    }

    public function getType() {
        return $this->_type;
    }

    public function doUpload($width = 1000, $height = 1000, $name = 'image', $types = 'gif|jpg|png', $size = 2048, $path = 'gallery') {
        if (!is_dir(IMAGEPATH . $path . '/')) mkdir(IMAGEPATH . $path, 0777, true);
        $parts      = explode('.', $_FILES[$name]['name']);
        $ext        = array_pop($parts);
        $filename   = array_shift($parts);
        $config['upload_path']      = IMAGEPATH . $path . '/';
        $config['allowed_types']    = $types;
        $config['max_size']         = $size;
        $config['max_width']        = $width;
        $config['max_height']       = $height;
        $config['overwrite']        = false;
        $config['encrypt_name']     = true;
        $config['remove_spaces']    = true;
        $this->upload->initialize($config);
        //$this->load->library('upload', $config);
        if (!$this->upload->do_upload($name)) {
            $error = $this->upload->display_errors();
            var_dump($width);
            return array(false, $error);
        } else {
            $data = $this->upload->data();
            $this->_filename    = $data['file_name'];
            $this->_width       = $data['image_width'];
            $this->_height      = $data['image_height'];
            $this->_type        = $data['image_type'];
            $this->_size        = $data['file_size'];
            return array(true, $data);
        }
    }

    public function createThumbnail($width = 200, $height = 150, $path = 'gallery', $quality = 90, $thumb_marker = '_thumb') {
        if (!is_dir(IMAGEPATH . $path . '/')) mkdir(IMAGEPATH . $path, 777, true);
        $this->_thumb_marker = $thumb_marker;

        $config['height'] = $height;
        $config['width']  = $width;

        // $size_data = getimagesize(IMAGEPATH.$path.'/'.$this->_filename);
        // if ($size_data[0] >= $size_data[1]) {
        //     $new_width          = ($size_data[0]/$size_data[1]) * $height;
        //     $config['height']   = $height;
        //     $config['width']    = $new_width;
        // } else {
        //     $new_height         = ($size_data[1]/$size_data[0]) * $width;
        //     $config['height']   = $new_height;
        //     $config['width']    = $width;
        // }

        $config['image_library']    = 'gd2';
        $config['source_image']     = IMAGEPATH.$path.'/'.$this->_filename;
        $config['overwrite']        = TRUE;
        $config['create_thumb']     = TRUE;
        $config['maintain_ratio']   = TRUE;
        $config['quality']          = $quality;
        $config['thumb_marker']     = $thumb_marker;
        $config['create_thumb']     = TRUE;
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }

    public function resize ( $width = 0, $height = 0, $path = '', $maintain_ratio = TRUE )
    {
        $fly_name  = $this->setFlyImageName($width, $height);
        $new_image = IMAGEPATH.$path.'/'.$fly_name;

        if ( $width && $height )
        {
            $config['image_library']  = 'gd2';
            $config['source_image']   = IMAGEPATH.$path.'/'.$this->_filename;
            $config['new_image']      = $new_image;
            $config['overwrite']      = TRUE;
            $config['create_thumb']   = FALSE;
            $config['maintain_ratio'] = $maintain_ratio;
            $config['width']          = $width;
            $config['height']         = $height;

            $this->image_lib->initialize($config);
            if ( ! $this->image_lib->resize() )
            {
                echo $this->image_lib->display_errors();
            }
        }
        return IMAGESRC.$path.'/'.$fly_name;
    }

    public function Delete($path = 'gallery') {
        delete_files(IMAGEPATH.$path.'/'.$this->_filename);
        $filename = IMAGEPATH.$path.'/'.$this->getFilenameThumb();
        if (file_exists($filename)) { delete_files($filename); }
        return $this->db->where('id', $this->_id)->delete($this->_table);
    }

    public function Save() {
        $query = false;

        if ($this->_id > 0) {
            $sql = "SELECT * FROM {$this->_table} WHERE id='{$this->_id}' LIMIT 1";
            $res = $this->db->query($sql)->row();
        }

        if (!empty($res)) {
            $sql = "UPDATE {$this->_table}
                SET
                    filename='{$this->_filename}',
                    size='{$this->_size}',
                    width='{$this->_width}',
                    height='{$this->_height}',
                    type='{$this->_type}'
                WHERE id='{$this->_id}'";
            return $res = $this->db->query($sql);
        }

        $sql = "INSERT INTO {$this->_table} SET
            filename='{$this->_filename}',
            size='{$this->_size}',
            width='{$this->_width}',
            height='{$this->_height}',
            type='{$this->_type}'";
        $res = $this->db->query($sql);
        return $this->db->insert_id();
    }

}
