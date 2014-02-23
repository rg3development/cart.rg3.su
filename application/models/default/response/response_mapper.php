<?php
/*
 * Model of news mapper
 * This classs dependence of image_item
 * @author rav <arudyuk@gmail.com>
 * @version 1.0
 *
 */

class Response_mapper extends MY_Model implements Mapper {

    public function  __construct($id = 0) {
        parent::__construct();
        $this->_table               = 'response_category';
        $this->_table_item          = 'response_item';
        $this->_table_image         = 'images';
        $this->_table_page          = 'pages';
        $this->_template['list']    = 'response/index';
        $this->load->model('response/response_category');
        $this->load->model('response/response_item');
    }

    public function get_path_to_image() {
        return $this->_path_to_image;
    }

    public function get_categories($parent_id = 0, $sort = 'desc', $sort_type = 'id') {
        $where        = $parent_id > 0 ? " where parent_id = {$parent_id}" : "";
        $sort        = $sort == 'asc' ? 'asc' : 'desc';
        $sort_type    = $sort_type == 'id' || $sort_type == 'parent_id' || $sort_type == 'title' ? $sort_type : 'id';
        $sql        = "select id, parent_id, title, show_title, count_per_page from {$this->_table} {$where} order by {$sort_type} {$sort}";
        $data        = $this->db->query($sql)->result_array();
        if (sizeof($data) == 0) return array();
        return $this->_create_collection($data, 'category');
    }

    public function get_category($id) {
        $sql = "select id, parent_id, title, show_title, count_per_page from {$this->_table} where id = {$id}";
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'category');
    }

    public function get_all_objects($parent_id = 0, $sort = 'desc', $sort_type = 'user_date') {
        $where        = $parent_id > 0 ? " where parent_id = {$parent_id}" : "";
        $sort        = $sort == 'asc' ? 'asc' : 'desc';
        $sql = "select id, parent_id, title, spec_link, is_spec_link, link_new_window, description, author, unix_timestamp(date_created) created, unix_timestamp(user_date) user_date from {$this->_table_item} {$where} order by {$sort_type} {$sort}";
        $data = $this->db->query($sql)->result_array();
        if ($data === false) return array();
        return $this->_create_collection($data, 'item');
    }

    public function get_object($id) {
        $sql = "select id, parent_id, title, spec_link, is_spec_link, link_new_window, description, author, unix_timestamp(date_created) created, unix_timestamp(user_date) user_date from {$this->_table_item} where id = {$id}";
        $res = $this->db->query($sql)->row_array();
        if (sizeof($res) == 0) return false;
        return $this->_get_object($res, 'item');
    }

    public function save($object) {
        if ($object instanceof Response_category) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", time());
                $sql = "update {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            show_title = {$this->db->escape($object->show_title)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($updated)}
                        where id = {$object->id}";
                if ($this->db->query($sql)) return true;
                else return false;
            } else {
                $created = date("Y-m-d H:i", $object->created);
                $sql = "insert into {$this->_table}
                        set title = {$this->db->escape($object->title)},
                            show_title = {$this->db->escape($object->show_title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            count_per_page = {$this->db->escape($object->count_per_page)},
                            date_updated = {$this->db->escape($created)},
                            date_created = {$this->db->escape($created)}";
                if ($this->db->query($sql)) return true;
                else return false;
            }
        } elseif ($object instanceof Response_item) {
            if ($object->id > 0) {
                $updated = date("Y-m-d H:i", time());
                $user_date = date("Y-m-d H:i", mktime($object->user_hour, $object->user_min, 0, $object->user_month, $object->user_day, $object->user_year));
                $sql = "update {$this->_table_item}
                        set title = {$this->db->escape($object->title)},
                            parent_id = {$this->db->escape($object->parent_id)},
                            is_spec_link = {$this->db->escape($object->is_spec_link)},
                            link_new_window = {$this->db->escape($object->link_new_window)},
                            spec_link = {$this->db->escape($object->spec_link)},
                            description = {$this->db->escape($object->description)},
                            author = {$this->db->escape($object->author)},
                            date_updated = {$this->db->escape($updated)},
                            user_date = {$this->db->escape($user_date)}
                        where id = {$object->id}";
                return $this->db->query($sql);
            } else {
                $user_date = date("Y-m-d H:i", mktime($object->user_hour, $object->user_min, 0, $object->user_month, $object->user_day, $object->user_year));
                $created = date("Y-m-d H:i", $object->created);
                if (mktime($object->user_hour, $object->user_min, 0, $object->user_month, $object->user_day, $object->user_year) == 0) {
                    $user_date = $created;
                }
                $sql = "insert into {$this->_table_item}
                            set title = {$this->db->escape($object->title)},
                                parent_id = {$this->db->escape($object->parent_id)},
                                link_new_window = {$this->db->escape($object->link_new_window)},
                                is_spec_link = {$this->db->escape($object->is_spec_link)},
                                spec_link = {$this->db->escape($object->spec_link)},
                                description = {$this->db->escape($object->description)},
                                author = {$this->db->escape($object->author)},
                                date_updated = {$this->db->escape($created)},
                                date_created = {$this->db->escape($created)},
                                user_date = {$this->db->escape($user_date)}";
                return $this->db->query($sql);
            }
        }
        else return false;
    }

    public function delete ($id = 0, $type = '') {
        if ($type == 'category') {
            $sql_news        = "delete from {$this->_table} where id = {$id}";
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

    public function get_page_content($page_id = 0) {
        $page_id           = (int)$page_id;
        $news_id           = (int)$this->input->get('news_id');
        $offset            = (int)$this->input->get('per_page');
        $sql               = "select url from {$this->_table_page} where id = {$page_id}";
        $page              = $this->db->query($sql)->row_array();
        $sql               = "select id, title, show_title, count_per_page from {$this->_table} where parent_id = {$page_id}";
        $response_category = $this->db->query($sql)->row_array();
        if (!isset($response_category['id'])) return '';
        $sql       = "select count(*) as count from {$this->_table_item} where parent_id = {$response_category['id']}";
        $tmp       = $this->db->query($sql)->row_array();
        $count_all = $tmp['count'];
        unset($tmp);

        $paginator = $this->_paginator($count_all, $response_category['count_per_page']);

        $sql       = "select id, title, description, author, link_new_window, is_spec_link is_spec_link, spec_link spec_link, unix_timestamp(user_date) created from {$this->_table_item} n
                      where parent_id = {$response_category['id']} order by user_date desc limit {$offset}, {$response_category['count_per_page']}";
        $response_list = $this->db->query($sql)->result_array();
        return $this->load->site_view($this->_template['list'], array('response_category' => $response_category, 'response_list' => $response_list, 'page_url' => $page['url'], 'page_id' => $page_id, 'offset' => $offset, 'paginator' => $paginator), true);
    }

    protected function _get_object($data = array(), $type = 'item') {
        if ($type == 'category') {
            $tmp_object                   = new Response_category();
            $tmp_object->id               = $data['id'];
            $tmp_object->parent_id        = !empty($data['parent_id'])       ? $data['parent_id']           : '';
            $tmp_object->title            = !empty($data['title'])           ? $data['title']               : '';
            $tmp_object->show_title       = !empty($data['show_title'])      ? $data['show_title']          : '';
            $tmp_object->count_per_page   = !empty($data['count_per_page'])  ? $data['count_per_page']      : '';
            return $tmp_object;
        } elseif ($type == 'item') {
            $tmp_object                   = new Response_item();
            $tmp_object->id               = $data['id'];
            $tmp_object->parent_id        = !empty($data['parent_id'])        ? $data['parent_id']          : '';
            $tmp_object->title            = !empty($data['title'])            ? $data['title']              : '';
            $tmp_object->author           = !empty($data['author'])           ? $data['author']             : '';
            $tmp_object->description      = !empty($data['description'])      ? $data['description']        : '';
            $tmp_object->is_spec_link     = !empty($data['is_spec_link'])     ? $data['is_spec_link']       : '';
            $tmp_object->link_new_window  = !empty($data['link_new_window'])  ? $data['link_new_window']    : '';
            $tmp_object->spec_link        = !empty($data['spec_link'])        ? $data['spec_link']          : '';
            $tmp_object->created          = !empty($data['created'])          ? $data['created']            : '';
            $user_date                    = !empty($data['user_date'])        ? $data['user_date']          : 0;
            $tmp_object->user_day         = date("d", $user_date);
            $tmp_object->user_month       = date("m", $user_date);
            $tmp_object->user_year        = date("Y", $user_date);
            $tmp_object->user_hour        = date("H", $user_date);
            $tmp_object->user_min         = date("i", $user_date);
            return $tmp_object;
        }
        return false;
    }

    private function _paginator ( $total_rows, $per_page )
    {
        $full_url = site_url() . $_SERVER['REQUEST_URI'];
        $full_url = explode('?', $full_url);
        $full_url = $full_url[0];

        // load catalog pagination config
        $config = $this->config->item('pagination_response');
        // setting preferences
        $config['base_url']   = $full_url.'?';
        $config['total_rows'] = $total_rows;
        $config['per_page']   = $per_page;

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

}