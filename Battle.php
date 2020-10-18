<?php

require_once("DB.php");

class Battle {
    public $battle_id;
    public $battle_code;
    public $home_id;
    public $away_id;
    public $winner_id;
    public $funds;
    public $points;

    public function __construct($params) {
        $this->battle_id = @$params["battle_id"];
        $this->battle_code = $params["battle_code"] ?? uniqid();
        $this->home_id = $params["home_id"];
        $this->away_id = $params["away_id"];
        $this->winner_id = @$params["winner_id"];
        $this->funds = @$params["funds"];
        $this->points = @$params["points"];
    }

    public function save() {
        $db = new DB();

        if ($this->battle_id) {
            $db->update("battle", $this, "battle_id");
        }
        else {
            $db->insert("battle", $this);
        }
    }

    public static function getBattleByCode($battle_code) {
        $db = new DB();
        $result = $db->query("battle")->where("battle_code", $battle_code)->execute()->fetch();
        return new Battle($result);
    }

    /**
     * @param string $column
     * @param string $value
     * @return Battle[]
     */
    public static function getBattles($column, $value) {
        if (!$column || !$value) {
            return null;
        }

        $db = new DB();
        $results = $db->query("battle")->where($column, $value)->execute()->fetchAll();

        $battles = [];
        foreach ($results as $result) {
            $battles[$result["battle_id"]] = new Battle($result);
        }

        return $battles;
    }
}
