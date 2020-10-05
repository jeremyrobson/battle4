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
        $results = $db
            ->query("job")
            ->execute()
            ->fetchAll();
        $jobs = [];
        foreach ($results as $result) {
            $jobs[$result["job_id"]] = new Job($result);
        }
        return $jobs;
    }

    public static function getJob($job_id) {
        $db = new DB();
        $result = $db
            ->query("job")
            ->where("job_id", $job_id)
            ->execute()
            ->fetch();
        return new Job($result);
    }
}