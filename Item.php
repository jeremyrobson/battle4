<?php

require_once("DB.php");

class Item {
    public $item_id;
    public $item_code;
    public $name;
    public $slot_id;
    public $class;
    public $type;
    public $pwr;
    public $def;
    public $hp;
    public $mp;
    public $str;
    public $agl;
    public $sta;
    public $mag;
    public $cost;

    public function __construct($params) {
        $this->item_id = @$params["item_id"];
        $this->item_code = $params["item_code"] ?? uniqid();
        $this->name = $params["name"];
        $this->slot_id = @$params["slot_id"];
        $this->class = $params["class"];
        $this->type = $params["type"];
        $this->pwr = @$params["pwr"];
        $this->def = @$params["def"];
        $this->hp = @$params["hp"];
        $this->mp = @$params["mp"];
        $this->str = @$params["str"];
        $this->agl = @$params["agl"];
        $this->sta = @$params["sta"];
        $this->mag = @$params["mag"];
        $this->cost = @$params["cost"];
    }

    public function save() {
        $db = new DB();

        if ($this->item_id) {
            $db->update("item", $this, "item_id");
        }
        else {
            $db->insert("item", $this);
        }
    }

    /**
     * @param string $column
     * @param string $value
     * @return Item[]
     */
    public static function getItems($column, $value) {
        if (!$column || !$value) {
            return null;
        }

        $db = new DB();
        $results = $db->query("item")->where($column, $value)->execute()->fetchAll();

        $spoils = [];
        foreach ($results as $result) {
            $spoils[$result["item_id"]] = new Item($result);
        }

        return $spoils;
    }

    /**
     * @param int $party_id
     * @return Item[]
     */
    public static function getItemsByPartyId($party_id) {
        $db = new DB();

        $results = $db
            ->query("item")
            ->join("item_party", "item_id")
            ->where("party_id", $party_id)
            ->execute()
            ->fetchAll();

        $units = [];

        foreach ($results as $result) {
            $units[$result["item_id"]] = new Item($result);
        }

        return $units;
    }

    /**
     * @param int $item_code
     * @return Item
     */
    public static function getItemByCode($item_code) {
        $db = new DB();

        $result = $db
            ->query("item")
            ->where("item_code", $item_code)
            ->execute()
            ->fetch();

        return new Item($result);
    }

    /**
     * @return array[]
     */
    public static function getShopItems() {
        $db = new DB();
        return $db
            ->query("item")
            ->join("item_party", "item_id")
            ->execute()
            ->fetchAll();
    }

    /**
     * @param int $item_id
     * @param int $party_id
     * @return int
     */
    public static function buyItem($item_id, $party_id) {
        $db = new DB();
        return $db->insert("item_party", [
            "item_id" => $item_id,
            "party_id" => $party_id
        ]);
    }
}
