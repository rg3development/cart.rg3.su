<?php
/*
 * Model of gallery mapper
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Gallery_mapper extends MY_Model implements Mapper {

    public function  __construct($id = 0) {
        parent::__construct();
        $this->_table               = 'gallery_category';
        $this->_table_item          = 'gallery_item';
        $this->_table_image         = 'images';
        $this->_table_page          = 'pages';
        $this->_path_to_image       =  IMAGESRC.'gallery';
        $this->_template['show']    = 'gallery/index';
        $this->_template['show2']   = 'gallery/index2';
        $this->load->model('gallery/gallery_category');
        $this->load->model('gallery/gallery_item');
    }

    public function get_path_to_image() {
        return $this->_path_to_image;
    }

    public function get_categories($parent_id = 0, $sort = 'desc', $sort_type = 'id') {
        $where      = $parent_id > 0 ? " where parent_id = {$parent_id}" : "";
        $sort       = $sort == 'asc' ? 'asc' : 'desc';
        $sort_type  = $sort_type == 'id' || $sort_type == 'parent_id' || $sort_type == 'title' ? $sort_type : 'id';
        $sql        = "select * from {$this->_table} {$where} order by {$sort_type} {$sort}";
        $data       = $this->db->query($sql)->result_array();
        if (sizeof($data) == 0) return array();
        return $this->_create_collection($data, 'category');
    }

    public function get_category($id) {
        $sql = "select * from {$this->_table} where id = {$id}";
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'category');
    }

    public function get_all_objects($parent_id = 0, $sort = 'asc', $sort_type = 'priority') {
        $where      = $parent_id > 0 ? " where parent_id = {$parent_id}" : "";
        $sort       = $sort == 'asc' ? 'asc' : 'desc';
        $sql = "select id, parent_id, title, image_id, description, priority, link, unix_timestamp(date_created) created from {$this->_table_item} {$where} order by {$sort_type} {$sort}";
        $data = $this->db->query($sql)->result_array();
        if ($data === false) return array();
        return $this->_create_collection($data, 'item');
    }

    public function get_object($id) {
        $sql = "select id, parent_id, title, image_id, description, priority, link, unix_timestamp(date_created) created from {$this->_table_item} where id = {$id}";
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'item');
    }

    public function save($object) {
        if ($object instanceof Gallery_category) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", $object->updated);
                $sql = "update {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            script_type = {$this->db->escape($object->script_type)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($updated)},
                            resize_width = {$this->db->escape($object->resize_width)},
                            resize_height = {$this->db->escape($object->resize_height)}
                        where id = {$object->id}";
                if ($this->db->query($sql)) return true;
                else return false;
            } else {
                $created = date("Y-m-d H:i", $object->created);
                $sql = "insert into {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            script_type = {$this->db->escape($object->script_type)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($created)},
                            resize_width = {$this->db->escape($object->resize_width)},
                            resize_height = {$this->db->escape($object->resize_height)},
                            date_created = {$this->db->escape($created)}";
                if ($this->db->query($sql)) return true;
                else return false;
            }
        } elseif ($object instanceof Gallery_item) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", time());
                $sql = "update {$this->_table_item}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            image_id = {$this->db->escape($object->image_id)},
                            link = {$this->db->escape($object->link)},
                            priority = {$this->db->escape($object->priority)},
                            description = {$this->db->escape($object->description)},
                            date_updated = {$this->db->escape($updated)}
                        where id = {$object->id}";
                return $this->db->query($sql);
            } else {
                $created = date("Y-m-d H:i", $object->created);
                $sql = "insert into {$this->_table_item}
                            set title = {$this->db->escape($object->title)},
                                parent_id = {$this->db->escape($object->parent_id)},
                                image_id = {$this->db->escape($object->image_id)},
                                link = {$this->db->escape($object->link)},
                                priority = {$this->db->escape($object->priority)},
                                description = {$this->db->escape($object->description)},
                                date_updated = {$this->db->escape($created)},
                                date_created = {$this->db->escape($created)}";
                return $this->db->query($sql);
            }
        }
        else return false;
    }

    public function delete ($id = 0, $type = '') {
        if ($type == 'category') {
            $sql_news       = "delete from {$this->_table} where id = {$id}";
            $sql_news_item  = "delete from {$this->_table_item} where parent_id = {$id}";
            $this->db->query($sql_news);
            $this->db->query($sql_news_item);
            return true;
        } elseif ($type == 'item') {
            $sql_news_item  = "delete from {$this->_table_item} where id = {$id}";
            $this->db->query($sql_news_item);
            return true;
        }
        return false;
    }

    public function get_page_content($page_id = 0) {
        $page_id          = (int)$page_id;
        $offset           = (int)$this->input->get('per_page');;
        $sql              = "SELECT id, script_type, count_per_page FROM {$this->_table} WHERE parent_id = {$page_id}";
        $gallery_category = $this->db->query($sql)->row_array();
        if (sizeof($gallery_category) == 0) {
            return '';
        }
        $sql       = "select count(*) as count from {$this->_table_item} where parent_id = {$gallery_category['id']}";
        $tmp       = $this->db->query($sql)->row_array();
        $count_all = $tmp['count'];
        unset($tmp);

        $paginator = $this->_paginator($count_all, $gallery_category['count_per_page']);


        $sql = "select n.id, n.title, n.image_id, n.description, n.link, i.filename filename
                from {$this->_table_item} n left join {$this->_table_image} i on n.image_id = i.id
                where n.parent_id = {$gallery_category['id']}
                order by n.priority asc limit {$offset}, {$gallery_category['count_per_page']}";
        $gallery = $this->db->query($sql)->result_array();
        foreach ($gallery as $key => $value) {
            $tmp = explode('.', $value['filename']);
            $gallery[$key]['thumbnail'] = $tmp[0].'_thumb.'.$tmp[1];
        }
        if ($gallery_category['script_type'] == 1) {
            $template = $this->_template['show2'];
        } else {
            $template = $this->_template['show'];
        }
        return $this->load->site_view($template, array('gallery' => $gallery, 'offset' => $offset, 'path'=> $this->_path_to_image, 'paginator' => $paginator), true);
    }

    public function _get_object($data = array(), $type = 'item') {
        if ($type == 'category') {
            $tmp_object = new Gallery_category();
            $tmp_object->id             = $data['id'];
            $tmp_object->parent_id      = $data['parent_id'];
            $tmp_object->script_type    = $data['script_type'];
            $tmp_object->title          = $data['title'];
            $tmp_object->count_per_page = $data['count_per_page'];
            $tmp_object->resize_width   = $data['resize_width'];
            $tmp_object->resize_height  = $data['resize_height'];
            return $tmp_object;
        } elseif ($type == 'item') {
            $tmp_object = new Gallery_item();
            $tmp_object->id          = $data['id'];
            $tmp_object->parent_id   = $data['parent_id'];
            $tmp_object->title       = $data['title'];
            $tmp_object->description = $data['description'];
            $tmp_object->link        = $data['link'];
            $tmp_object->image_id    = $data['image_id'];
            $tmp_object->created     = $data['created'];
            $tmp_object->priority    = $data['priority'];
            return $tmp_object;
        }
        return false;
    }

    public function to_down($id, $parent_id) {
        $id             = (int)$id;
        $parent_id      = (int)$parent_id;
        $sql            = "select priority from {$this->_table_item} where id = {$id}";
        $current_page   = $this->db->query($sql)->row_array();
        if (sizeof($current_page) == 0) return false;
        $sql        = "select id, priority from {$this->_table_item} where priority > {$current_page['priority']} and parent_id = {$parent_id} order by priority asc limit 0,1";
        $next_page  = $this->db->query($sql)->row_array();
        if (sizeof($next_page) > 0) {
            $this->db->query("start transaction");
            $sql_next       = "update {$this->_table_item} set priority = {$current_page['priority']} where id = {$next_page['id']} and parent_id = {$parent_id}";
            $sql_current    = "update {$this->_table_item} set priority = {$next_page['priority']} where id = {$id} and parent_id = {$parent_id}";
            if ($this->db->query($sql_next) && $this->db->query($sql_current)) {
                $this->db->query("commit");
                return true;
            } else {
                $this->db->query("rollback");
            }
        }
        return false;
    }

    public function to_up($id, $parent_id) {
        $id             = (int)$id;
        $parent_id      = (int)$parent_id;
        $sql            = "select priority from {$this->_table_item} where id = {$id}";
        $current_page   = $this->db->query($sql)->row_array();
        if (sizeof($current_page) == 0) return false;
        $sql        = "select id, priority from {$this->_table_item} where priority < {$current_page['priority']} and parent_id = {$parent_id} and priority > 0 order by priority desc limit 0,1";
        $prev_page  = $this->db->query($sql)->row_array();
        if (sizeof($prev_page) > 0) {
            $this->db->query("start transaction");
            $sql_prev       = "update {$this->_table_item} set priority = {$current_page['priority']} where id = {$prev_page['id']} and parent_id = {$parent_id}";
            $sql_current    = "update {$this->_table_item} set priority = {$prev_page['priority']} where id = {$id} and parent_id = {$parent_id}";
            if ($this->db->query($sql_prev) && $this->db->query($sql_current)) {
                $this->db->query("commit");
                return true;
            } else {
                $this->db->query("rollback");
            }
        }
        return false;
    }

    private function _paginator ( $total_rows, $per_page )
    {
        $full_url = site_url() . $_SERVER['REQUEST_URI'];
        $full_url = explode('?', $full_url);
        $full_url = $full_url[0];

        // load catalog pagination config
        $config = $this->config->item('pagination_gallery');
        // setting preferences
        $config['base_url']   = $full_url.'?';
        $config['total_rows'] = $total_rows;
        $config['per_page']   = $per_page;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

}
