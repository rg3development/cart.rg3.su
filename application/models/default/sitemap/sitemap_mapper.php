<?php

class Sitemap_mapper extends MY_Model
{

	public function  __construct()
	{
		parent::__construct();
		$this->_template['index']    = 'sitemap/index';
		$this->_template['map_item'] = 'sitemap/map_item';
		$this->_template['map_list'] = 'sitemap/map_list';
	}

	public function get_page_content ( $page_id = 0 )
	{
		$map_array = $this->page_mapper->get_allshow_pages();
		$map       = $this->_get_pages_tree($map_array, $this->_template['map_list'], $this->_template['map_item']);

        $data['map'] = $map;
		return $this->load->site_view($this->_template['index'], $data, TRUE);
	}

	protected function _get_pages_tree($map_array = array(), $list_tmp = '', $item_tmp = '', $active_id = 0, $params = array()) {
		if ( !is_array($map_array) || (sizeof($map_array) == 0) )
		{
			return false;
		}
		if ( empty($list_tmp) )
		{
			$list_tmp = $this->_template['map_list'];
		}
		if ( empty($item_tmp) )
		{
			$item_tmp = $this->_template['map_item'];
		}

		$map       = '';
		$max_level = sizeof($map_array) - 1;

		for ($i = $max_level; $i >= 0 ; $i--)
		{
			if ( !empty($map_array[$i]) && sizeof($map_array[$i] ) > 0)
			{
				foreach ( $map_array[$i] as $key => $page )
				{
					$j = $i + 1;
					$tmp_submenu = '';
					if ( !empty($html_menu[$j]) && sizeof($html_menu[$j]) > 0 )
					{
						foreach($html_menu[$j] as $key => $subpage)
						{
							if ($page->id == $subpage['parent_id'])
							{
								$tmp_submenu .= $subpage['string'];
							}
						}
					}
					if ( !empty($tmp_submenu) )
					{
						$tmp_submenu = $this->load->site_view($list_tmp, array('pages_block' => $tmp_submenu, 'params' => $params), true);
					}

					$content_data['page']      = $page;
					$content_data['submenu']   = $tmp_submenu;
					$content_data['level']     = $i;
					$content_data['active_id'] = (int) $active_id;
					$content_data['params']    = $params;

					$html_menu[$i][] = array (
						'parent_id' => $page->parent_id,
						'string'    => $this->load->site_view($item_tmp, $content_data, TRUE)
					);
				}
			}
		}

		if (!empty($html_menu[0])) {
			foreach ($html_menu[0] as $menu_item) {
				$map .= $menu_item['string'];
			}
		} else {
			$map = '';
		}
		return $map;
	}

}