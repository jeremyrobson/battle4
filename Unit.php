<?php

require_once("NameGenerator.php");

class Unit {
    public $id;
    public $team;
    public $job_class;
    public $name;
    public $hp;
    public $agl;
    public $sta;

    public function __construct($team, $job_class) {
        $this->id = uniqid();
        $this->team = $team;
        $this->name = NameGenerator::generate_name();
        $this->job_class = $job_class;
        $this->hp = 100;
        $this->agl = random_int(3, 9);
    }

    public static function fromArray($arr) {
        $unit = new Unit($arr["team"], $arr["job_class"]);
        $unit->name = $arr["name"];
        $unit->hp = $arr["hp"];
        $unit->agl = $arr["agl"];
        return $unit;
    }

    public function toArray() {
        return [
            "team" => $this->team,
            "job_class" => $this->job_class,
            "name" => $this->name,
            "hp" => $this->hp,
            "agl" => $this->agl,
            "sta" => $this->sta
        ];
    }
}