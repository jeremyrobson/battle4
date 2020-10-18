<?php

require_once("Unit.php");
require_once("DB.php");

class Party {
    public $party_id;
    public $user_id;
    public $name;

    public function __construct($params) {
        $this->party_id = @$params["party_id"];
        $this->user_id = @$params["user_id"];
        $this->name = $params["name"];
    }

    /**
     * @param int $user_id
     * @param int $party_id
     * @return Party
     */
    public static function getParty($party_id) {
        $db = new DB();

        $result = $db
            ->query("party")
            //->where("user_id", $user_id)
            ->where("party_id", $party_id)
            ->execute()
            ->fetch();

        $party = new Party($result);

        return $party;
    }

    /**
     * @param int $user_id
     * @return Party[]
     */
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
            $parties[$arr["party_id"]] = $party;
        }

        return $parties;
    }

    public function save() {
        $db = new DB();

        if ($this->party_id) {
            $db->update("party", $this, "party_id");
        }
        else {
            $this->party_id = $db->insert("party", $this);
        }
    }

}