<?php

require_once("DB.php");
require_once("Action.php");

class Job {
    public $job_id;
    public $name;
    public $sprite;
    public $move_cost;
    public $actions;

    public function __construct($arr) {
        $this->job_id = $arr["job_id"];
        $this->name = $arr["name"];
        $this->sprite = $arr["sprite"];
        $this->move_cost = $arr["move_cost"];
        $this->actions = Action::getActions($arr["job_id"]);
    }

    public static function getJobs() {
        $db = new DB();
        $jobs = $db->query("job")->execute()->fetchAll();
        $objects = [];
        foreach ($jobs as $job) {
            $objects[$job["job_id"]] = new Job($job);
        }
        return $objects;
    }
}