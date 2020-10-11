<?php

require_once("DB.php");

class Action {
    public $action_id;
    public $name;
    public $range;
    public $spread;
    public $action_cost;

    public function __construct($params) {
        $this->action_id = $params["action_id"];
        $this->name = $params["name"];
        $this->range = $params["range"];
        $this->spread = $params["spread"];
        $this->action_cost = $params["action_cost"];
    }

    public static function getActions($job_id) {
        $db = new DB();
        $actions = $db
            ->query("action")
            ->join("job_action", "action_id")
            ->where("job_id", $job_id)
            ->execute()
            ->fetchAll();
        $objects = [];
        foreach ($actions as $action) {
            $objects[$action["action_id"]] = new Action($action);
        }
        return $objects;
    }
}