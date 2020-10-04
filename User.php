<?php

require_once("DB.php");

class User {

    public $username;
    public $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
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
        return new User($user["username"], $user["password"]);
    }
}