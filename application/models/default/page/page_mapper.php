<?php
/*
 * Model of page mapper
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Page_mapper extends MY_Model {

    protected $_table = 'pages';

    public function  __construct() {
        parent::__construct();
        $this->load->model('page/page_item');
        $this->_path_to_image = IMAGESRC.'page';
    }

    public function get_path_to_image() {
        return $this->_path_to_image;
    }

    public function get_all_pages() {
        $sql       = "select max(level) level from {$this->_table}";
        $max_level = $this->db->query($sql)->row()->level;
        $sitemap   = array();
        for ($i = 0; $i <= $max_level; $i++) {
            $res = $this->_get_pages_array("level = {$i}");
            if ( sizeof($res) > 0 ) {
                $sitemap[$i] = $this->_create_collection($res);
            }
        }
        return $sitemap;
    }

    public function get_allshow_pages() {
        $sql       = "select max(level) level from {$this->_table}";
        $max_level = $this->db->query($sql)->row()->level;
        $sitemap   = array();
        for ($i = 0; $i <= $max_level; $i++)
        {
            $res = $this->_get_pages_array("`show` = 1 AND level = {$i}");
            if ( sizeof($res) > 0 )
            {
                $sitemap[$i] = $this->_create_collection($res);
            }
        }
        return $sitemap;
    }

    public function get_page($id) {
        $sql                = "select * from {$this->_table} where id = {$id}";
        $res                = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_page_object($res);
    }

    public function get_menu($max_level = 1, $parent_id = 0) {
        $sitemap = array();
        if ($parent_id == 0) {
            for ($i = 0; $i <= $max_level; $i++) {
                $res = $this->_get_pages_array("`show` = 1 and `level` = {$i}");
                if (sizeof($res) > 0) $sitemap[$i] = $this->_create_collection($res);
            }
            return $sitemap;
        }
        for ($i = 0; $i <= $max_level; $i++) {
            $res = $this->_get_pages_array("`show` = 1 AND `level` = {$i} AND `parent_id` = {$parent_id}");
            if (sizeof($res) > 0) $sitemap = $this->_create_collection($res);
        }
        return $sitemap;
    }

    public function delete_page ($id) {
        $id         = (int)$id;
        $pages_id   = $this->_get_pages_tree_down($id);
        $pages_id[] = $id;
        $pages_id   = join(",", $pages_id);

        $sql = "DELETE FROM `{$this->_table}` WHERE `id` IN ({$pages_id})";
        $this->db->query($sql);
    }

    public function check_url_exist($id, $url) {
        $sql    = "select id from {$this->_table} where url = {$this->db->escape($url)}";
        $res    = $this->db->query($sql)->result_array();
        if (sizeof($res) == 1) if ($res[0]['id'] == $id) return true;
        if (sizeof($res) == 0) return true;
        return false;
    }

    /* Page to down */
    public function page_to_down($id) {
        $id             = (int)$id;
        $sql            = "select level, priority from {$this->_table} where id = {$id}";
        $current_page   = $this->db->query($sql)->row_array();
        if (sizeof($current_page) == 0) return false;
        $sql = "select id, priority from {$this->_table} where level = {$current_page['level']} and priority >{$current_page['priority']} order by priority asc limit 0,1";
        $next_page = $this->db->query($sql)->row_array();
        if (sizeof($next_page) > 0) {
            $this->db->query("start transaction");
            $sql_next       = "update {$this->_table} set priority = {$current_page['priority']} where id = {$next_page['id']}";
            $sql_current    = "update {$this->_table} set priority = {$next_page['priority']} where id = {$id}";
            if ($this->db->query($sql_next) && $this->db->query($sql_current)) {
                $this->db->query("commit");
                return true;
            } else {
                $this->db->query("rollback");
            }
        }
        return false;
    }

    /* Page to up */
    public function page_to_up($id) {
        $id             = (int)$id;
        $sql            = "select level, priority from {$this->_table} where id = {$id}";
        $current_page   = $this->db->query($sql)->row_array();
        // print_r($current_page);
        if (sizeof($current_page) == 0) return false;
        // $sql            = "select id, priority from {$this->_table} where level = {$current_page['level']} and priority < {$current_page['priority']} and priority > 0 order by priority desc limit 0,1";
        $sql            = "select id, priority from {$this->_table} where level = {$current_page['level']} and priority < {$current_page['priority']} order by priority desc limit 0,1";
        $prev_page      = $this->db->query($sql)->row_array();
        // print_r($prev_page);
        // exit;
        if (sizeof($prev_page) > 0) {
            $this->db->query("start transaction");
            $sql_prev       = "update {$this->_table} set priority = {$current_page['priority']} where id = {$prev_page['id']}";
            $sql_current    = "update {$this->_table} set priority = {$prev_page['priority']} where id = {$id}";
            if ($this->db->query($sql_prev) && $this->db->query($sql_current)) {
                $this->db->query("commit");
                return true;
            } else {
                $this->db->query("rollback");
            }
        }
        return false;
    }


    protected function _get_pages_array($where = 'level = 0',
                                        $limit = '',
                                        $offset = 0,
                                        $order = 'priority asc,id asc'
                                     ) {
        $sql = "select * from {$this->_table}";
        if (!empty($where)) $sql .= " where {$where}";
        if (!empty($limit)) $sql .= " limit {$offset}, {$limit}";
        if (!empty($order)) $sql .= " order by {$order}";
        return $this->db->query($sql)->result_array();
    }

    public function get_parent_ids ( $id = 0 )
    {
        return $this->_get_pages_tree_up($id);
    }

    protected function _get_pages_tree_up ( $page_id, &$result_array = array() ) {
        $page_list = $this->db->select('*')->from($this->_table)->where('id', $page_id)->get()->result();
        foreach ( $page_list as $key => $page )
        {
            $result_array[] = $page->id;
            $this->_get_pages_tree_up($page->parent_id, $result_array);
        }
        return $result_array;
    }

    /* Get all subpage for page */
    protected function _get_pages_tree_down ( $page_id, &$result_array = array() ) {
        $page_list = $this->db->select('id')->from($this->_table)->where('parent_id', $page_id)->get()->result();
        foreach ( $page_list as $key => $page ) {
            $result_array[] = $page->id;
            $this->_get_pages_tree_down($page->id, $result_array);
        }
        return $result_array;
    }

    public function save_page ( $object ) {
        if ( get_class($object) != 'Page_item') {
            return false;
        }
        // set level for current page
        if ( $object->parent_id ) {
            $parent_page    = $this->get_page($object->parent_id);
            $new_parent_lvl =  $parent_page->level;
            $object_level   = $new_parent_lvl + 1;
        } else {
            $object_level   = 0;
            $new_parent_lvl = -1;
        }
        // update page item
        if ( $object->id > 0 ) {
            $old_page = $this->get_page($object->id);
            if ( $old_page->parent_id )
            {
                $old_parent_page = $this->get_page($old_page->parent_id);
                $old_parent_lvl  = $old_parent_page->level;
            } else {
                $old_parent_lvl = -1;
            }
            $delta_lvl = $new_parent_lvl - $old_parent_lvl;
            $sql = "
                UPDATE
                    {$this->_table}
                SET
                    `parent_id`    = {$object->parent_id},
                    `url`          = {$this->db->escape($object->url)},
                    `priority`     = {$object->priority},
                    `meta`         = {$this->db->escape($object->meta)},
                    `description`  = {$this->db->escape($object->description)},
                    `keywords`     = {$this->db->escape($object->keywords)},
                    `level`        = {$object_level},
                    `title`        = {$this->db->escape($object->title)},
                    `show_title`   = {$object->show_title},
                    `show`         = {$object->show},
                    `alias`        = {$this->db->escape($object->alias)},
                    `show_alias`   = {$object->show_alias},
                    `template`     = {$this->db->escape($object->template)},
                    `image_bottom` = {$object->image_bottom}
                WHERE
                    `id`           = {$object->id}
            ";
            if ( $this->db->query($sql) )
            {
                if ( $delta_lvl )
                {
                    // update level for subpages
                    $sub_pages = $this->_get_pages_tree_down($object->id);
                    foreach ( $sub_pages as $subpage_id )
                    {
                        $query = "UPDATE `{$this->_table}` SET `level` = `level` + {$delta_lvl} WHERE `id` = {$subpage_id}";
                        $this->db->query($query);
                    }
                }
                return $object->id;
            } else {
                return FALSE;
            }
        // insert page item
        } else {
            $sql = "INSERT INTO {$this->_table} SET
                `parent_id`    = {$object->parent_id},
                `url`          = {$this->db->escape($object->url)},
                `priority`     = {$object->priority},
                `meta`         = {$this->db->escape($object->meta)},
                `description`  = {$this->db->escape($object->description)},
                `keywords`     = {$this->db->escape($object->keywords)},
                `level`        = {$object_level},
                `title`        = {$this->db->escape($object->title)},
                `show_title`   = {$object->show_title},
                `show`         = {$object->show},
                `alias`        = {$this->db->escape($object->alias)},
                `show_alias`   = {$object->show_alias},
                `template`     = {$this->db->escape($object->template)},
                `image_bottom` = {$object->image_bottom}
            ";
            if ( $this->db->query($sql) )
            {
                return $this->db->insert_id();
            } else {
                return FALSE;
            }
        }
    }

    protected function _get_page_object($data = array()) {
        $tmp_object = new Page_item();
        $tmp_object->id           = $data['id'];
        $tmp_object->title        = $data['title'];
        $tmp_object->show_title   = $data['show_title'];
        $tmp_object->alias        = $data['alias'];
        $tmp_object->show_alias   = $data['show_alias'];
        $tmp_object->meta         = $data['meta'];
        $tmp_object->keywords     = $data['keywords'];
        $tmp_object->description  = $data['description'];
        $tmp_object->url          = $data['url'];
        $tmp_object->level        = $data['level'];
        $tmp_object->parent_id    = $data['parent_id'];
        $tmp_object->priority     = $data['priority'];
        $tmp_object->show         = $data['show'];
        $tmp_object->template     = $data['template'];
        $tmp_object->image_bottom = $data['image_bottom'];
        return $tmp_object;
    }

    protected function _create_collection($data = array()) {
        if (sizeof($data) == 0) return false;
        $object_collection = array();
        foreach ($data as $data_element) {
            $object_collection[] = $this->_get_page_object($data_element);
        }
        return $object_collection;
    }

}