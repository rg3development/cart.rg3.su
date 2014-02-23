<?php
/*
 * Модель для авторизаци пользователя
 * @author rav
 * @version 1.0
 */

class Auth_Model extends MY_Model {
    /*
     * Диапазон времени с момента отправки письма
     * с в который действует уникальная ссылка для воссатновления пароля
     */
    public $delta_time = 10800;
    public function __construct() {
        parent::__construct();
    }

    /*
     * Авторизация пользователя
     * @param $username, $password
     * @return true, false
     */
    public function auth_user($username, $password) {
        $query = $this->db->get_where('users', array(
            'login'    => mysql_real_escape_string($username),
            'password' => md5($password),
            'active'   => 1,
        ));
        if($query->num_rows() != 1) {
            return false;
        } else {
            $row = $query->row();
            $user_data['id']   = $row->id;
            $user_data['login']= $row->login;
            $user_data['email']= $row->email;
            $this->session->set_userdata($user_data);
            return true;
        }
    }

    /*
     * Проверка состояния пользователя, авторизован или нет
     * @return true, false
     */
    public function is_auth() {
        if($this->session->userdata('login') === false) return false;
        $email = $this->session->userdata('email');
        $users = $this->db->query("SELECT * FROM users WHERE active = 1 AND email='".$email."'" );
        if ($users->num_rows() > 0) return true;
        return false;
    }

    /*
     * Проверка существования e-mail
     * @param $email
     * @return true, false
     */
    public function is_empty_email($email) {
        $sql = "SELECT * FROM users WHERE email = '".$email."'";
        $result = $this->db->query($sql)->result_array();
        return count($result) > 0 ? false : true;
    }

    /*
     * Проверка существования login
     * @param $login
     * @return true, false
     */
    public function is_empty_login($login) {
        $sql = "SELECT * FROM users WHERE login = '".$login."'";
        $result = $this->db->query($sql)->result_array();
        return count($result) > 0 ? false : true;
    }

    /*
     * Восстановление пароля
     * @param $login, $hash, $newpass
     * @return true, false
     */
    public function new_pass($login, $hash, $newpass) {
        $user = $this->db->query( "SELECT *, UNIX_TIMESTAMP(forgettime) as unixtime FROM users WHERE login='".$login."'" )->row();
        $code = sha1(md5($user->email.$user->salt.$user->forgettime));
        if ($code == $hash && time() >= $user->unixtime && time() <= ($user->unixtime + $this->delta_time)) {
            $this->db->query( "UPDATE users SET password = '".md5($newpass)."', salt = 0, forgettime = '0000-00-00'  WHERE login='".$login."'" );
            return true;
        }
        return false;
    }

    /*
     * Восстановление пароля. Проверка правильности уникальной ссылки
     * @param $login, $hash
     * @return true, false
     */
    public function is_hash_pass ($login, $hash) {
        $user = $this->db->query("SELECT *, UNIX_TIMESTAMP(forgettime) as unixtime FROM users WHERE login='".$login."'")->row();
        $code = sha1(md5($user->email.$user->salt.$user->forgettime));
        if ($code == $hash && time() >= $user->unixtime && time() <= ($user->unixtime + $this->delta_time)) {
            return true;
        }
        return false;
    }

    /*
     * Восстановление пароля. Генерация униклальнй ссылки.
     * @param $email
     * @return link
     */
    public function forget_pass ($email) {
        $time_now = date("Y-m-d H:i:s", time());
        $salt = rand(100000, 100000000);
        $code = sha1(md5($email.$salt.$time_now));
        $email = mysql_real_escape_string($email);
        $user = $this->db->query( "SELECT * FROM users WHERE email='".$email."'" )->row();
        $this->db->query( "UPDATE users SET salt = ".$salt.", forgettime = '".$time_now."'  WHERE email='".$email."'" );
        return $user->login.'/'.$code;
    }
}