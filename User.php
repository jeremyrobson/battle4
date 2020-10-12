<?php

require_once("DB.php");

class User {

    public $user_id;
    public $username;
    public $password;
    public $funds;

    public function __construct($arr) {
        $this->user_id = $arr["user_id"];
        $this->username = $arr["username"];
        $this->password = $arr["password"];
        $this->funds = $arr["funds"];
    }

    public static function insertUser($user) {
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        $user->funds = 500;
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

    public static function getUserByUserId($user_id) {
        $db = new DB();
        $user = $db->query("user")->where("user_id", $user_id)->execute()->fetch();
        return new User($user);
    }

    public function save() {
        $db = new DB();
        $db->update("user", $this, "user_id");
    }

    /**
     * @param int $party_id
     * @param Unit $unit
     * @param int $cost
     * @throws Exception
     */
    public function hireUnit($party_id, $unit, $cost) {

        if ($cost <= $this->funds) {
            $this->funds -= $cost;
            $this->save();
            $unit_id = Unit::insertUnit($_SESSION["user_id"], $party_id, $unit);
            if ($unit_id) {
                $_SESSION["messages"][] = "$unit->name was hired!";
                header("Location: index.php?page=menu_party&party_id=$party_id");
            } else {
                throw new Exception("ERROR: COULD NOT INSERT UNIT");
            }
        }
        else {
            throw new Exception("NOT ENOUGH FUNDS");
        }
    }
}