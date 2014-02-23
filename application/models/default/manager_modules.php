
<?php
/*
 * Модель работы с модулями и настроками сайта
 * влючает в себя фабрику и ряд
 * вспомогательных методов
 *
 * @author rav <arudyuk@gmail.com>
 * @version 2.2
 */

class Manager_modules extends MY_Model {
	protected $_table            = 'modules';
    protected $_table_p            = 'pages';
    protected $_table_mp        = 'module_pages';
    protected $_table_settings    = 'settings';

    public function  __construct() {
        parent::__construct();
    }

    /*
     *  Возвращаем спиосок данных о модулях из БД в виде массива
     *  @return array();
     */
    public function get_list_module() {
        $modules = $this->db->select("{$this->_table}.id as id, {$this->_table}.title as title")
                            ->from($this->_table)->get()->result_array();
        return $modules;
    }

    /*
     *  Возвращаем спиосок данных о модулях привязанных к заданной странице
     *  @param int $page_id
     *  @return array();
     */
    public function get_page_module($page_id = 0) {
        $page_id = (int)$page_id;
        if ($page_id == 0) return array();
        $modules = $this->db->select("{$this->_table}.id as id, {$this->_table}.title as title, {$this->_table}.classname as classname")->from($this->_table_mp)
                            ->join($this->_table, $this->_table.'.id = '.$this->_table_mp.'.module_id', 'left')
                            ->where('page_id', $page_id)->order_by("{$this->_table_mp}.priority asc")
                            ->get()->result_array();
        return $modules;
    }

    /*
     *  Удаляем модули привязанные к странице
     *  @param int $page_id
     *  @return <bool>;
     */
    public function remove_page_module($page_id = 0) {
        if ($page_id == 0) return false;
        $this->db->where('page_id', $page_id)->delete($this->_table_mp);
        return true;
    }

    /*
     *  Привязывает типы модулей к странице сайта с
     *  учетов позиции на странице
     *  @param int $page_id
     *  @param array $modules
     *  @return <bool>;
     */
    public function set_page_module($page_id = 0, $modules = array()) {
        if ($page_id == 0 || !is_array($modules)) return false;
        $val = array();
        foreach ($modules as $priority => $module_id) {
            $priority  = (int)$priority + 1;
            $module_id = (int)$module_id;
            $val[] = "({$page_id}, {$module_id}, {$priority})";
        }
        $val = join(",", $val);
        $sql = "INSERT INTO {$this->_table_mp} (page_id, module_id, priority) VALUES {$val}";
        $this->db->query($sql);
        return true;
    }

    public function get_page_data($url, $error_url = 'error404') {
        if (empty($url) || $url == 'main' ||  $url == '/') {
            $sql = "select * from `{$this->_table_p}` where `template` = 'main' order by id asc limit 0,1";
            $page = $this->db->query($sql)->row_array();
            if (count($page) == 0) {
                $sql = "select * from `{$this->_table_p}` order by id asc limit 0,1";
                $page = $this->db->query($sql)->row_array();
            }
        } else {
            $page = $this->db->get_where($this->_table_p, array('url' => xss_clean($url), 'show' => 1), 1, 0)->row_array();
        }
        if (sizeof($page) == 0) {
            show_404();
            die;
        }
        $modules = $this->db->select($this->_table.'.*')->from($this->_table_mp)
                            ->join($this->_table, $this->_table.'.id = '.$this->_table_mp.'.module_id', 'left')
                            ->where($this->_table_mp.'.page_id', $page['id'])
                            ->order_by($this->_table_mp.'.priority asc')->get()->result_array();
        $sql                = "select id, value from {$this->_table_settings} order by id asc";

        $tmp                            = $this->db->query($sql)->result_array();
        $site_settings                  = array();
        $site_settings['title']         = $tmp[0]['value'];
        $site_settings['description']   = $tmp[1]['value'];
        $site_settings['keywords']      = $tmp[2]['value'];
        $site_settings['logo']          = $tmp[5]['value'];
        return array('page' => $page, 'modules' => $modules, 'site_settings' => $site_settings);
    }

    public function settings ()
    {
        $settings = $this->db
            ->select('*')
            ->from($this->_table_settings)
            ->order_by('id', 'ASC')
            ->get()->result_array();
        $result = array ();
        foreach ( $settings as $key => $value )
        {
            $result[$value['name']] = $value['value'];
        }
        return $result;
    }

    public function set_cart_serttings ( $cart_data = array() )
    {
        $data = array();
        foreach ( $cart_data as $data_name => $data_val )
        {
            $data['value'] = $data_val;
            $this->db->where('name', $data_name);
            $this->db->update($this->_table_settings, $data);
        }

    }

}
