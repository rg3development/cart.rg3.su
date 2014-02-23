<?php
// class Auth extends Admin_Controller {
class Auth extends CI_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->model('auth_model');
        $this->load->library('email');
        $this->load->library('form_validation');
    }

    public function login() {

        $this->form_validation->set_rules('login', 'логин', 'trim|required|min_length[5]|max_length[255]|callback_auth_check');
        $this->form_validation->set_rules('password', 'пароль', 'trim|required|min_length[5]|max_length[255]');
        $this->form_validation->set_message('required', 'незаполено поле: %s');
        $this->form_validation->set_message('min_length', '%s не меньше 5 символов');
        $this->form_validation->set_message('max_length', '%s не больше 255 символов');
        $this->form_validation->set_message('auth_check', 'логин или пароль введене неверно');
        $this->form_validation->set_error_delimiters('', '<br/>');
        if (!empty($_POST)) {
            if ($this->form_validation->run() == FALSE) {
                $this->load->admin_view('auth/login', array(), FALSE, FALSE);
            } else {
                if ($this->auth_model->auth_user($this->input->post('login'), $this->input->post('password'))) {
                    redirect(base_url().'admin/map/');
                } else {
                    $this->load->admin_view('auth/login', array('auth_fail' => 'логин или пароль неверны'), FALSE, FALSE);
                }
            }
        } else {
            $this->load->admin_view('auth/login', array(), FALSE, FALSE);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url().'/admin/map');
    }


    /*
     * Забыли пароль
     */
    public function forgetpass() {
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле незаполено поле: %s');
        $this->form_validation->set_message('valid_email', 'неверно заполнено поле: %s');
        $this->form_validation->set_message('forget_email_check', 'пользователь с таким e-mail не существует');
        $this->form_validation->set_rules('email', 'e-mail','trim|required|valid_email|callback_forget_email_check');
        if ($this->form_validation->run()) {
            $result = $this->auth_model->forget_pass($this->input->post('email'));
            $config['mailtype'] = 'html';
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;
            $this->email->initialize($config);
            $this->email->from(EMAIL, 'Forget password');
            $this->email->to($this->input->post('email'));
            $this->email->subject('Forget password!');
            $this->email->message('От Вашего имени был сделан запрос на восстановление пароля, для смены пароля перейдите по <a href="'.base_url().'admin/auth/newpass/'.$result.'">ссылке</a>. Ссылка будет активна в течении 3-х часов.');
            $this->email->send();
            $this->email->clear();
            $this->load->admin_view('auth/finish2', array(), FALSE, FALSE);
            return;
        }
        $this->load->admin_view('auth/forgetpass', array(), FALSE, FALSE);
        return;
    }


     /*
     * Задание нового пароля
     */
    public function newpass($login = '', $hash = '') {
        if ((empty($login) || empty($hash)) && empty($_POST['newpass'])) redirect(base_url().'error_404/');
        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_message('required', 'поле незаполено поле: %s');
        $this->form_validation->set_rules('newpass', 'новый пароль','trim|required');
        if ($this->form_validation->run()) {
            $result = $this->auth_model->new_pass($this->input->post('login'), $this->input->post('hash'), $this->input->post('newpass'));
            if ($result)
                $this->load->admin_view('auth/finish3', array(), FALSE, FALSE);
            else redirect(base_url().'error_404/');
            return;
        }
        $login = $this->input->post('login') ? $this->input->post('login') : $login;
        $hash = $this->input->post('hash') ? $this->input->post('hash') : $hash;
        if (!$this->auth_model->is_hash_pass($login, $hash)) redirect(base_url().'error_404/');
        $this->load->admin_view('auth/newpass', array('hash' => $hash, 'login' => $login), FALSE, FALSE);
        return;
    }


    /*
     * Проверка существоания email для восстановления пароля
     */
    public function forget_email_check($email) {
        if ($this->auth_model->is_empty_email($email)) return false;
        return true;
    }
}