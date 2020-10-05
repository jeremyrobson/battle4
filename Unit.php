<?php

require_once("Job.php");
require_once("NameGenerator.php");

class Unit {
    public $unit_id;
    public $party_id;
    public $job_id;
    public $name;
    public $hp;
    public $agl;
    public $sta;

    public function __construct($params) {
        $this->unit_id = @$params["unit_id"] ?? uniqid();
        $this->party_id = $params["party_id"];
        $this->job_id = $params["job_id"];
        $this->name = $params["name"];
        $this->hp = $params["hp"];
        $this->agl = $params["agl"];
        $this->sta = $params["sta"];
    }

    public static function insertUnit($user_id, $party_id, $params) {
        $db = new DB();

        //todo: validate user_id

        $unit = new Unit($params);
        $unit->party_id = $party_id;
        return $db->insert("unit", $unit);
    }

    public static function generateUnit($party_id) {
        $jobs = Job::getJobs();
        $job = $jobs[array_rand($jobs)];

        return new Unit([
            "party_id" => $party_id,
            "job_id" => $job->job_id,
            "name" => NameGenerator::generate_name(),
            "hp" => 100,
            "agl" => random_int(3, 9),
            "sta" => 100
        ]);
    }
}