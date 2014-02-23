<?

class Comments_mapper extends MY_Model {
    protected $_template;
    protected $_template_ok;
    protected $_tables = array();
    protected $data    = array();

    public function  __construct() {
        parent::__construct();
        $this->load->model('comments/comments_item');
        $this->data['approved_message'] = COMMENTS_UNAPPROVED_MESSAGE;
        $this->_template                = 'comments/list';
        $this->_template_ok             = 'comments/ok';
        $this->_tables['comments']      = 'comments';
        $this->_tables['pages']         = 'pages';
    }

    public function get_page_content($page_id = 0) {
        $request_uri = $_SERVER['REQUEST_URI'];
        $template = $this->_template;
        if (!empty($_POST)) {
            $this->form_validation->set_rules('name', '<strong>имя</strong>', 'xss_clean|trim|required|min_length[1]|max_length[25]');
            $this->form_validation->set_rules('email', '<strong>e-mail</strong>', 'required|valid_email');
            $this->form_validation->set_rules('message', '<strong>сообщение</strong>', 'xss_clean|trim|required|min_length[1]|max_length[1000]');
            $this->form_validation->set_message('required', '<span>поле %s обязательно для заполнения</span><br/>');
            $this->form_validation->set_message('min_length', '<span>поле %s должно быть больше 1-го символа</span><br/>');
            $this->form_validation->set_message('max_length', '<span>поле %s должно быть меньше 32-х символов</span><br/>');
            $this->form_validation->set_message('valid_email', '<span>поле <strong>e-mail</strong> имеет неверный формат</span><br/>');
            $this->form_validation->set_error_delimiters('', '');
            if ($this->form_validation->run()) {
                $comment = new Comments_item();
                $comment->page_url = $request_uri;
                $comment->name     = $this->input->post('name');
                $comment->email    = $this->input->post('email');
                $comment->message  = $this->input->post('message');
                if ( defined('COMMENTS_DEFAULT_VALUE') ) {
                    $comment->approved = COMMENTS_DEFAULT_VALUE;
                    if ( COMMENTS_DEFAULT_VALUE ) {
                        $this->data['approved_message'] = COMMENTS_UNAPPROVED_MESSAGE;
                    } else {
                        $this->data['approved_message'] = COMMENTS_APPROVED_MESSAGE;
                    }
                }
                $comment_id = $this->save($comment);
                $this->data['error'] = FALSE;
                $template = $this->_template_ok;
            } else {
                $this->data['error'] = TRUE;
                $template = $this->_template;
            }
        }
        $this->data['comments'] = $this->get_page_comments($request_uri);
        return $this->load->site_view($template, $this->data, true);
    }

    protected function get_page_comments ($page_uri = '') {
        if ( $page_uri ) {
            $page_uri = preg_replace('/&per_page=(.*)/', '', $page_uri);
            $page_uri = $this->db->escape($page_uri);
            $query = "
                SELECT * FROM {$this->_tables['comments']}
                WHERE `is_deleted` = 0 AND `approved` = 1 AND `page_url` = {$page_uri}";
            return $this->db->query($query)->result();
        }
        return array();
    }

    public function get_comments_list ($approved = 0) {
        $query = "
            SELECT
                c.id id, c.page_url page_url, c.name name, c.email email, c.message message, c.date date,
                c.is_deleted is_deleted, c.approved approved, p.title title
            FROM {$this->_tables['comments']} c LEFT JOIN {$this->_tables['pages']} p ON c.page_url LIKE CONCAT('%',p.url,'%')
            WHERE `is_deleted` = 0 AND `approved` = {$approved}
            ORDER BY p.title ASC, c.date DESC";
        return $this->db->query($query)->result();
    }

    public function save ($object) {
        if (!($object instanceof Comments_item)) {
            return FALSE;
        }
        if ( $object->id > 0 ) {
            return $object->id;
        }
        /*
        if (substr($object->page_url,0,1) == '/') {
            $object->page_url = substr($object->page_url,1);
        }*/
        $created = date("Y-m-d H:i:s", time());
        $query = "
            INSERT INTO
                {$this->_tables['comments']}
            SET
                `page_url` = {$this->db->escape($object->page_url)},
                `name`     = {$this->db->escape($object->name)},
                `email`    = {$this->db->escape($object->email)},
                `message`  = {$this->db->escape($object->message)},
                `date`     = {$this->db->escape($created)},
                `approved` = {$this->db->escape($object->approved)}
        ";
        if ( $this->db->query($query) ) {
            return $this->db->insert_id();
        }
        return FALSE;
    }
}