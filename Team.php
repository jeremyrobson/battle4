<?php

require_once("Unit.php");

class Team {
    public $name;
    public $units;

    public function __construct($name) {
        $this->name = $name;
        $this->units = [];
    }

    public function addUnit($unit) {
        $this->units[] = $unit;
    }

    public static function load() {
        $team = new Team($_SESSION["team_name"] ?? "player");
        $units = $_SESSION["units"];
        foreach ($units as $unit) {
            $team->addUnit(Unit::fromArray($unit));
        }
        return $team;
    }

    public function save() {
        $units = [];
        foreach ($this->units as $unit) {
            $units[] = $unit->toArray();
        }
        $_SESSION["team_name"] = $this->name;
        $_SESSION["units"] = $units;
    }

}