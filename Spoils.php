<?php

require_once("DB.php");

class Spoils {
    public $spoils_id;
    public $battle_id;
    public $type;
    public $user_id;
    public $party_id;
    public $unit_id;
    public $value;
    public $item_id;
    public $applied;

    public function __construct($params) {
        $this->spoils_id = @$params["spoils_id"];
        $this->battle_id = $params["battle_id"];
        $this->type = $params["type"];
        $this->user_id = $params["user_id"];
        $this->party_id = $params["party_id"];
        $this->unit_id = @$params["unit_id"];
        $this->value = @$params["value"];
        $this->item_id = @$params["item_id"];
        $this->applied = $params["applied"];
    }

    public function save() {
        $db = new DB();

        if ($this->spoils_id) {
            $db->update("spoils", $this, "spoils_id");
        }
        else {
            $db->insert("spoils", $this);
        }
    }

    /**
     * @param string $column
     * @param string $value
     * @return Spoils[]
     */
    public static function getSpoils($column, $value) {
        if (!$column || !$value) {
            return null;
        }

        $db = new DB();
        $results = $db->query("spoils")->where($column, $value)->execute()->fetchAll();

        $spoils = [];
        foreach ($results as $result) {
            $spoils[$result["spoils_id"]] = new Spoils($result);
        }

        return $spoils;
    }
}
