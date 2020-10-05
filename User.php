<?php

require_once("DB.php");

class User {

    public $user_id;
    public $username;
    public $password;

    public function __construct($arr) {
        $this->user_id = $arr["user_id"];
        $this->username = $arr["username"];
        $this->password = $arr["password"];
    }

    public static function insertUser($user) {
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        $db = new DB();
        return $db->insert("user", $user);
    }

    public static function getUser($column, $value) {
        if (!$column || !$value) {
            return null;
        }

        $db = new DB();
        $users = $db->query("user")->where($column, $value)->execute()->fetchAll();
        $user = reset($users);
        return new User($user);
    }
}