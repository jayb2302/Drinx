<?php
class User {
    public $user_id;
    public $username;
    public $email;
    public $password;
    public $account_status_id;
    public $join_date;
    public $last_login;
    public $is_admin;

    public function __construct($username, $email, $password, $account_status_id, $is_admin = 0) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->account_status_id = $account_status_id;
        $this->is_admin = $is_admin;
    }
}
?>