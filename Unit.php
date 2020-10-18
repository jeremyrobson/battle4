<?php

require_once("Job.php");
require_once("NameGenerator.php");

class Unit {
    public $unit_id;
    public $party_id;
    public $job_id;
    public $name;
    public $hp;
    public $mp;
    public $str;
    public $agl;
    public $sta;
    public $mag;

    public function __construct($params) {
        $this->unit_id = @$params["unit_id"];
        $this->party_id = @$params["party_id"];
        $this->job_id = $params["job_id"];
        $this->name = $params["name"];
        $this->hp = intval($params["hp"]);
        $this->mp = intval($params["mp"]);
        $this->str = intval($params["str"]);
        $this->agl = intval( $params["agl"]);
        $this->sta = intval($params["sta"]);
        $this->mag = intval($params["mag"]);
    }

    /**
     * @param int $user_id
     * @param int $party_id
     * @param Unit $unit
     * @return int
     */
    public static function insertUnit($user_id, $party_id, $unit) {
        $db = new DB();

        //todo: validate user_id

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
            "mp" => 100,
            "str" => random_int(3, 9),
            "agl" => random_int(3, 9),
            "mag" => random_int(3, 9),
            "sta" => 100
        ]);
    }

    /**
     * @param int $party_id
     * @return Unit[]
     */
    public static function getUnitsByPartyId($party_id) {
        $db = new DB();

        $results = $db
            ->query("unit")
            ->where("party_id", $party_id)
            ->execute()
            ->fetchAll();

        $units = [];

        foreach ($results as $result) {
            $units[$result["unit_id"]] = new Unit($result);
        }

        return $units;
    }

    public static function getUnit($unit_id) {
        $db = new DB();

        $result = $db
            ->query("unit")
            ->where("unit_id", $unit_id)
            ->execute()
            ->fetch();

        return new Unit($result);
    }

    public function save() {
        $db = new DB();

        if ($this->unit_id) {
            $db->update("unit", $this, "unit_id");
        }
        else {
            $db->insert("unit", $this);
        }
    }
}