<?php

class Cart_mapper extends MY_Model implements Mapper
{

	public function  __construct() {
		parent::__construct();
		$this->_table_item	= 'cart_item';
		$this->_table_order	= 'cart_order';
		$this->_template['index']    = 'cart/index';
		$this->_template['checkout'] = 'cart/checkout';
		$this->_template['thankyou'] = 'cart/thankyou';

		$this->load->library('cart');
		$this->load->model('cart/cart_item');

		$this->cart->product_name_rules = '\d\D';
		$this->cart->product_id_rules   = '^.';
	}

	public function get_object($id) {
		$sql = "select id, parent_id, title, description, show_title from {$this->_table_item} where id = {$id}";
		$res = $this->db->query($sql)->row_array();
		if (sizeof($res) == 0) return false;
		return $this->_get_object($res);
	}

	public function get_all_objects($page_id = 0, $order = 'id desc') {
		$sql = "select id, parent_id, title, description, show_title from {$this->_table_item}";
		if ($page_id > 0) $sql .= " where parent_id = {$this->db->escape($page_id)}";
		$sql .= " order by $order";
		$data = $this->db->query($sql)->result_array();
		if ($data === false) return array();
		return $this->_create_collection($data);
	}

	public function get_page_content($page_id = 0)
	{
		$this->form_validation->set_rules('full_name', 'Фамилия, имя', 'required');
		$this->form_validation->set_rules('email', 'Электронный адрес', 'required|valid_email');
		$this->form_validation->set_rules('phone', 'Телефон', 'required');
		$this->form_validation->set_rules('address', 'Адрес', 'required');

		$this->form_validation->set_message('required', '<span>Поле "%s" не заполнено</span>');
		$this->form_validation->set_message('valid_email', '<span>Введен недействительный электронный адрес.</span>');

		if ( isset($_POST['type']) )
		{
			if ( $_POST['type'] == 'order' )
			{
				return $this->load->site_view($this->_template['checkout'], array(), true);
			}
			if ( $_POST['type'] == 'save' )
			{
				if ( $this->form_validation->run() )
				{
					if ( $this->cart->total() )
					{
						// save order
						$data = array();
						$now  = date("Y-m-d H:i:s", time());
						$data['full_name']       = $this->input->post('full_name');
						$data['phone']           = $this->input->post('phone');
						$data['address']         = $this->input->post('address');
						$data['email']           = $this->input->post('email');
						$data['comments']        = $this->input->post('comments');
						$data['order_total']     = $this->cart->total();
						$data['positions_total'] = $this->cart->total_items();
						$data['items_total']     = count($this->cart->contents());
						$data['date_created']    = $now;
						$this->db->insert('cart_order', $data);

						$order_id = $this->db->insert_id();

						// save cart item
						$data = array();
						foreach ( $this->cart->contents() as $item )
						{
							$now = date("Y-m-d H:i:s", time());
							$data['item_id']      = $item['id'];
							$data['order_id']     = $order_id;
							$data['base_price']   = $item['price'];
							$data['qty']          = $item['qty'];
							$data['date_created'] = $now;
							$cart_data[] = $data;
						}
						$this->db->insert_batch('cart_item', $cart_data);

						$this->_send_mails($this->input->post('email'), $order_id);
						$this->cart->destroy();
					} else {
						redirect(base_url('catalog'));
					}
					return $this->load->site_view($this->_template['thankyou'], array(), true);
				} else {
					return $this->load->site_view($this->_template['checkout'], array(), true);
				}
			}
		}
		return $this->load->site_view($this->_template['index'], array(), true);
	}

	private function _send_mails ( $user_email, $order_id )
	{
		$cart_settings = $this->manager_modules->settings();

		// список товаров в заказе
		$purchases = '
			<table style="width:100%;">
				<tr>
					<td>Наименование</td>
					<td>Количество</td>
					<td>Цена</td>
					<td>Сумма</td>
					<td>Артикул</td>
				</tr>
		';
		foreach ( $this->cart->contents() as $cart_item )
		{
			$purchases .= "
				<tr>
					<td>{$cart_item['name']}</td>
					<td>{$cart_item['qty']}</td>
					<td>{$cart_item['price']} руб.</td>
					<td>{$cart_item['subtotal']} руб.</td>
					<td>{$cart_item['options']['article']}</td>
				</tr>
			";
		}
		$purchases .= "
			<tr>
				<td>Сумма заказа:</td>
				<td></td>
				<td></td>
				<td>" . $this->cart->format_number($this->cart->total()) . "</td>
			</tr>
		";
		$purchases .= '</table><br/>';

		// информация о покупателе
		$sql = "
			SELECT
				*
			FROM
				`cart_item`
					JOIN
						`cart_order`
					ON
						cart_order.id = cart_item.order_id
			WHERE
				cart_order.id = '{$order_id}'
		";
		$db_result = $this->db->query($sql)->row_array();
		$userinfo = '<br/><div>';
		$userinfo .= '<p>Имя - '.$db_result['full_name'].'</p>';
		$userinfo .= '<p>Адрес - '.$db_result['address'].'</p>';
		$userinfo .= '<p>Телефон - '.$db_result['phone'].'</p>';
		$userinfo .= '<p>Электронная почта - '.$db_result['email'].'</p>';
		$userinfo .= '<p>Комментарии - '.$db_result['comments'].'</p>';
		$userinfo .= '</div></br>';

		// формат сообщения письма оператору
		$operator_message = '<html><header></header><body><div>';
		$operator_message .= $cart_settings['CART_OPERATOR_MESSAGE'];
		$operator_message .= '</br></div>';
		$operator_message .= $purchases;
		$operator_message .= '<br/><div>';
		$operator_message .= $userinfo;
		$operator_message .= '</body></html>';

		// отправка письма оператору
		$this->_send_mail(
			$cart_settings['CART_OPERATOR_SENDER_EMAIL'],
			$cart_settings['CART_OPERATOR_SENDER_NAME'],
			$cart_settings['CART_OPERATOR_EMAIL'],
			$cart_settings['CART_OPERATOR_SUBJECT'],
			$operator_message
		);

		// формат сообщения письма покупателю
		$client_message = '<html><header></header><body><div>';
		$client_message .= $cart_settings['CART_CLIENT_MESSAGE'];
		$client_message .= '</br></div>';
		$client_message .= $purchases;
		$client_message .= '<br/><div>';
		$client_message .= '</body></html>';

		// отправка письма покупателю
		$this->_send_mail(
			$cart_settings['CART_CLIENT_SENDER_EMAIL'],
			$cart_settings['CART_CLIENT_SENDER_NAME'],
			$user_email,
			$cart_settings['CART_CLIENT_SUBJECT'],
			$client_message
		);
	}

	private function _send_mail ( $from_email, $from_name, $to_email, $subject, $message )
	{
		// Load Email library
		$this->load->library('email');
		// Setting Email Preferences
		$config['mailtype'] = 'html';
		// Sending Email
		$this->email->initialize($config);
		$this->email->from($from_email, $from_name);
		$this->email->to($to_email);
		$this->email->subject($subject);
		$this->email->message($message);
		$this->email->send();
	}

	public function save($object) {
		if ($object instanceof Cart_order)
		{
			if ($object->id > 0) {
				$updated = date("Y-m-d H:i", time());
				$sql = "update {$this->_table_order}
						set phone = {$this->db->escape($object->phone)},
							address = {$this->db->escape($object->address)},
							name_first = {$object->name_first},
							name_last = {$this->db->escape($object->name_last)},
							email = {$this->db->escape($object->email)},
							order_total = {$this->db->escape($order_total)},
							items_total = {$this->db->escape($items_total)},
							positions_total = {$this->db->escape($positions_total)},
							items_total = {$this->db->escape($items_total)},
							date_updated = {$this->db->escape($updated)}
						where id = {$object->id}";
				if ($this->db->query($sql)) {
					return $object->id;
				} else return false;
			}
			$created = date("Y-m-d H:i", time());
			$sql = "insert into {$this->_table_item}
					set phone = {$this->db->escape($object->phone)},
						address = {$this->db->escape($object->address)},
						name_first = {$object->name_first},
						name_last = {$this->db->escape($object->name_last)},
						email = {$this->db->escape($object->email)},
						order_total = {$this->db->escape($order_total)},
						items_total = {$this->db->escape($items_total)},
						positions_total = {$this->db->escape($positions_total)},
						items_total = {$this->db->escape($items_total)},
						date_updated = {$this->db->escape($created)},
						date_created = {$this->db->escape($created)}";
			if ($this->db->query($sql)) {
				return $object->id;
			} else return false;
		}

		if ($object instanceof Cart_item)
		{
			if ($object->id > 0) {
				$updated = date("Y-m-d H:i", time());
				$sql = "update {$this->_table_item}
						set title = {$this->db->escape($object->title)},
							parent_id = {$this->db->escape($object->parent_id)},
							show_title = {$object->show_title},
							description = {$this->db->escape($object->description)},
							date_updated = {$this->db->escape($updated)}
						where id = {$object->id}";
				if ($this->db->query($sql)) {
					return $object->id;
				} else return false;
			}
			$created = date("Y-m-d H:i", time());
			$sql = "insert into {$this->_table_item}
					set title = {$this->db->escape($object->title)},
						parent_id = {$this->db->escape($object->parent_id)},
						show_title = {$object->show_title},
						description = {$this->db->escape($object->description)},
						date_updated = {$this->db->escape($created)},
						date_created = {$this->db->escape($created)}";
			if ($this->db->query($sql)) {
				return $object->id;
			} else return false;
		}
	}

	protected function _get_object($data = array()) {
		$tmp_object					= new Cart_item();
		$tmp_object->id				= $data['id'];
		$tmp_object->parent_id		= $data['parent_id'];
		$tmp_object->title			= $data['title'];
		$tmp_object->show_title		= $data['show_title'];
		$tmp_object->description	= $data['description'];
		return $tmp_object;
	}
}
