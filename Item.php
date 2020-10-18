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
     * @param string $column
     * @param string $value
     * @return Item[]
     */
    public static function getAllItems() {
        $db = new DB();
        $results = $db->query("item")->execute()->fetchAll();

        $spoils = [];
        foreach ($results as $result) {
            $spoils[$result["item_id"]] = new Item($result);
        }

        return $spoils;
    }
}
