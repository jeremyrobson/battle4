<?php

require_once("Unit.php");
require_once("DB.php");

class Party {
    public $party_id;
    public $user_id;
    public $name;
    public $units; //todo: remove this, get units in other ways

    public function __construct($params) {
        $this->party_id = $params["party_id"];
        $this->user_id = $params["user_id"];
        $this->name = $params["name"];
        $this->units = [];
    }

    /*
    public function addUnit($unit) {
        $this->units[] = $unit;
    }
    */

    public static function getParty($user_id, $party_id) {
        $db = new DB();

        $result = $db
            ->query("party")
            ->query("party")
            ->where("user_id", $user_id)
            ->where("party_id", $party_id)
            ->execute()
            ->fetch();

        $party = new Party($result);

        $results = $db
            ->query("unit")
            ->where("party_id", $party_id)
            ->execute()
            ->fetchAll();

        foreach ($results as $result) {
            $party->units[] = new Unit($result);
        }

        return $party;
    }

    public static function getParties($user_id) {
        $db = new DB();

        $parties = [];

        $results = $db
            ->query("party")
            ->where("user_id", $user_id)
            ->execute()
            ->fetchAll();

        foreach ($results as $arr) {
            $party = new Party($arr);

            $party->units = $db
                ->query("unit")
                ->where("party_id", $arr["party_id"])
                ->execute()
                ->fetchAll();

            $parties[$arr["party_id"]] = $party;
        }

        return $parties;
    }

    public static function insertParty($user_id, $params) {
        $db = new DB();
        $party = new stdClass(); //units makes this not work with class definition
        $party->user_id = $user_id;
        $party->name = $params["name"];
        return $db->insert("party", $party);
    }

}