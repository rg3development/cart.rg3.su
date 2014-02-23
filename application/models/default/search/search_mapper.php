<?php

/*
 * Поисковый движок
 *
 * @author rav <arudyuk@rg3.su>
 * @version 1.5
 * @copyright RG3 Development
 */

class Search_mapper extends MY_Model {

    protected $_tables;
    protected $_select_content_symb;

    public function __construct() {
        parent::__construct();
        $this->_tables['news_category'] = 'news_category';
        $this->_tables['news_item']     = 'news_item';
        $this->_tables['text_item']     = 'text_item';
        $this->_tables['pages']         = 'pages';
        $this->_template['index']       = 'search/index';
        $this->_select_content_symb     = 200;
    }

    public function get_page_content() {
        $sentence = isset($_GET['s']) ? $_GET['s'] : '';
        $result = array();
        if ($this->uri->segment(1)!='search2') {
            $result += $this->_get_text_content($sentence);
        }
        if ($this->uri->segment(1)!='search1') {
            $result += $this->_get_news_content($sentence);
        }
        if ($result) {
            foreach ($result as $key => $page) {
                $first_pos = mb_strpos($result[$key]['content'], $sentence);
                $result[$key]['content'] = mb_substr($result[$key]['content'], $first_pos, $this->_select_content_symb);
                $result[$key]['content'] = str_replace($sentence, '<b>' . $sentence . '</b>', $result[$key]['content']);
            }
        }
        return $this->load->site_view($this->_template['index'], array('content' => $result), true);
    }

    protected function _get_text_content($sentence = '') {
        if (empty($sentence)) return array();
        $sql = "select t.parent_id parent_id, t.description description, p.url url
                from {$this->_tables['text_item']} t inner join {$this->_tables['pages']} p on p.id=t.parent_id
                where t.description like '%".$sentence."%' and p.show = 1";
        $pages = $this->db->query($sql)->result_array();
        $content_url_array = array();
        foreach ($pages as $page) {
            if ($page['url'] != 'dev' && $page['url'] != 'error404') {
                if ($page['url'] == '') $page['url'] = 'main';
                $content = $this->_parse_string($page['description']);
                $content_url_array[] = array('content' => mb_strtolower($content), 'url' => $page['url']);
            }
        }
        return $content_url_array;
    }

    protected function _get_news_content($sentence = '') {
        if (empty($sentence)) return array();
        $sql = "select li.id id, l.parent_id parent_id, l.description description, p.url url
                from {$this->_tables['news_category']} li inner join {$this->_tables['news_item']} l on li.parent_id = l.id inner join {$this->_tables['pages']} p on p.id=l.parent_id
                where l.description like '%".$sentence."%' and p.show = 1";
        $pages = $this->db->query($sql)->result_array();
        $content_url_array = array();
        $content = '';
        foreach ($pages as $page) {
            $content .= str_replace('<br/>', '', $page['description']);
            $content_url_array[] = array('content' => mb_strtolower($content), 'url' => $page['url'].'?news_id='.$page['id']);
        }
        return $content_url_array;
    }

    protected function _parse_string($str) {
        $str = trim($str);
        $str = preg_replace("/[^\x20-\xFF]/", "", @strval($str));
        $str = preg_replace("/&(.+?);/", "", @strval($str));
        $str = strip_tags($str);
        $str = htmlspecialchars($str, ENT_QUOTES);
        return $str;
    }

}
