<?php
/*
 * Model of banner mapper
 *
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 */

class Banner_mapper extends MY_Model implements Mapper {

    public function  __construct($id = 0) {
        parent::__construct();
        $this->_table               = 'banner_category';
        $this->_table_item          = 'banner_item';
        $this->_table_image         = 'images';
        $this->_table_page          = 'pages';
        $this->_path_to_image       =  UPLOAD_BANNER_IMAGE;
        $this->_template['show']    = 'banner/index';
        $this->load->model('banner/banner_category');
        $this->load->model('banner/banner_item');
    }

    public function get_path_to_image() {
        return $this->_path_to_image;
    }

    public function get_categories($parent_id = 0, $sort = 'desc', $sort_type = 'id') {
        $where       = $parent_id > 0 ? " where parent_id = {$parent_id}" : "";
        $sort        = $sort == 'asc' ? 'asc' : 'desc';
        $sort_type   = $sort_type == 'id' || $sort_type == 'parent_id' || $sort_type == 'title' ? $sort_type : 'id';
        $sql         = "select id, parent_id, title, resize_width, resize_height, count_per_page from {$this->_table} {$where} order by {$sort_type} {$sort}";
        $data        = $this->db->query($sql)->result_array();
        if (sizeof($data) == 0) return array();
        return $this->_create_collection($data, 'category');
    }

    public function get_category($parent_id, $type = 'parent') {
        $parent_id = (int)$parent_id;
        if ($type == 'parent') {
            $sql = "select id, parent_id, title, resize_width, resize_height, count_per_page from {$this->_table} where parent_id = {$parent_id}";
        } elseif ($type == 'object') {
            $sql = "select id, parent_id, title, resize_width, resize_height, count_per_page from {$this->_table} where id = {$parent_id}";
        }
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'category');
    }

    public function get_all_objects($parent_id = 0, $sort = 'ASC', $sort_type = 'priority', $display = '') {
        $where   = "WHERE 1 ";
        $where  .= $parent_id > 0 ? " AND `parent_id` = {$parent_id}" : "";
        $where  .= $display != '' ? " AND `display` = {$display}" : "";
        $sort    = $sort == 'ASC' ? 'ASC' : 'DESC';
        $sql     = "SELECT `id`, `parent_id`, `title`, `image_id`, `mini_image_id`, `description`, `priority`, `link`, `link_new_window`, `display`, unix_timestamp(`date_created`) created FROM {$this->_table_item} {$where} ORDER BY {$sort_type} {$sort}";
        $data    = $this->db->query($sql)->result_array();
        if ($data === false) return array();
        return $this->_create_collection($data, 'item');
    }

    public function get_object($id) {
        $sql = "SELECT id, parent_id, title, image_id, mini_image_id, description, priority, link, link_new_window, display, unix_timestamp(date_created) created from {$this->_table_item} where id = {$id}";
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'item');
    }

    public function save($object) {
        if ($object instanceof Banner_category) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", $object->updated);
                $sql = "update {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            resize_width = {$this->db->escape($object->resize_width)},
                            resize_height = {$this->db->escape($object->resize_height)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($updated)}
                        where id = {$object->id}";
                if ($this->db->query($sql)) return true;
                else return false;
            } else {
                $created = date("Y-m-d H:i", $object->created);
                $sql = "insert into {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            resize_width = {$this->db->escape($object->resize_width)},
                            resize_height = {$this->db->escape($object->resize_height)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($created)},
                            date_created = {$this->db->escape($created)}";
                return $this->db->query($sql);
            }
        } elseif ($object instanceof Banner_item) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", time());
                $sql = "update {$this->_table_item}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            image_id = {$this->db->escape($object->image_id)},
                            mini_image_id = {$this->db->escape($object->mini_image_id)},
                            weight = {$this->db->escape($object->weight)},
                            link = {$this->db->escape($object->link)},
                            link_new_window = {$this->db->escape($object->link_new_window)},
                            priority = {$this->db->escape($object->priority)},
                            display = {$this->db->escape($object->display)},
                            description = {$this->db->escape($object->description)},
                            date_updated = {$this->db->escape($updated)}
                        where id = {$object->id}";
                return $this->db->query($sql);
            } else {
                $sql = "
                    SELECT
                        *
                    FROM
                        {$this->_table_item}
                    WHERE
                        `parent_id` = {$this->db->escape($object->parent_id)}
                ";
                $created = date("Y-m-d H:i", $object->created);
                $sql = "insert into {$this->_table_item}
                            set title = {$this->db->escape($object->title)},
                                parent_id = {$this->db->escape($object->parent_id)},
                                image_id = {$this->db->escape($object->image_id)},
                                mini_image_id = {$this->db->escape($object->mini_image_id)},
                                weight = {$this->db->escape($object->weight)},
                                link = {$this->db->escape($object->link)},
                                link_new_window = {$this->db->escape($object->link_new_window)},
                                priority = {$this->db->escape($object->priority)},
                                display = {$this->db->escape($object->display)},
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
            $sql_news         = "delete from {$this->_table} where id = {$id}";
            $sql_news_item    = "delete from {$this->_table_item} where parent_id = {$id}";
            $this->db->query($sql_news);
            $this->db->query($sql_news_item);
            return true;
        } elseif ($type == 'item') {
            $sql_news_item    = "delete from {$this->_table_item} where id = {$id}";
            $this->db->query($sql_news_item);
            return true;
        }
        return false;
    }

    public function _get_object($data = array(), $type = 'item') {
        if ($type == 'category') {
            $tmp_object = new Banner_category();
            $tmp_object->id             = $data['id'];
            $tmp_object->parent_id      = $data['parent_id'];
            $tmp_object->title          = $data['title'];
            $tmp_object->resize_width   = $data['resize_width'];
            $tmp_object->resize_height  = $data['resize_height'];
            $tmp_object->count_per_page = $data['count_per_page'];
            return $tmp_object;
        } elseif ($type == 'item') {
            $tmp_object = new Banner_item();
            $tmp_object->id              = $data['id'];
            $tmp_object->parent_id       = $data['parent_id'];
            $tmp_object->title           = $data['title'];
            $tmp_object->description     = $data['description'];
            $tmp_object->link            = $data['link'];
            $tmp_object->link_new_window = $data['link_new_window'];
            $tmp_object->display         = $data['display'];
            $tmp_object->image_id        = $data['image_id'];
            $tmp_object->mini_image_id   = $data['mini_image_id'];
            $tmp_object->created         = $data['created'];
            $tmp_object->priority        = $data['priority'];
            return $tmp_object;
        }
        return false;
    }

    public function to_down($id, $parent_id) {
        $id              = (int)$id;
        $parent_id       = (int)$parent_id;
        $sql             = "select priority from {$this->_table_item} where id = {$id}";
        $current_page    = $this->db->query($sql)->row_array();
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
        $sql            = "select id, priority from {$this->_table_item} where priority < {$current_page['priority']} and parent_id = {$parent_id} and priority > 0 order by priority desc limit 0,1";
        $prev_page      = $this->db->query($sql)->row_array();
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

    public function get_widget($id = 1, $order = 'ASC') {
        $id = (int) $id;
        $banner_category = $this->get_category($id, 'object');
        $banner_items    = $this->get_all_objects($id, $order, 'priority', 1);
        $banner = array();
        $banner['category'] = $banner_category;
        foreach ($banner_items as $key => $item) {
            $banner['items'][$key]['item'] = $item;
            $banner['items'][$key]['maxi'] = new Image_item($item->image_id);
        }
        return $banner;
    }

}