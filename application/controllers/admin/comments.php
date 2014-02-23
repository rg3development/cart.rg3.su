<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends Admin_Controller
{

    protected $_template;

    public function __construct() {
        parent::__construct();
        $this->load->model('comments/comments_mapper', 'comments');
        $this->_template    = 'comments/index';
    }

    public function index() {
        $this->template_data['comments']['approved']   = $this->comments->get_comments_list(1);
        $this->template_data['comments']['unapproved'] = $this->comments->get_comments_list(0);
        $this->load->admin_view($this->_template, $this->template_data);
    }

    public function approved ( $comment_id = 0, $approve_value = 0 ) {
        if ($comment_id) {
            $approve_value = $this->db->escape($approve_value);
            $comment_id    = $this->db->escape($comment_id);
            $query = "
                UPDATE
                    `comments`
                SET
                    `approved` = {$approve_value}
                WHERE
                    `id` = {$comment_id}
            ";
            return $this->db->query($query);
        }
        return FALSE;
    }
}