<?php

session_start();

require_once("Battle.php");
require_once("Job.php");
require_once("Action.php");
require_once("Party.php");
require_once("Unit.php");

$battle_code = $_GET["battle_code"];

if (!$battle_code) {
    return;
}

$battle = Battle::getBattleByCode($battle_code);

if (!$battle) {
    return;
}

$battle_data = [];
$battle_data["battle"] = $battle;
$battle_data["map"]["width"] = 16;
$battle_data["map"]["height"] = 16;

$battle_data["jobs"] = Job::getJobs();

foreach ($battle_data["jobs"] as $job_id => &$job) {
    $job->actions = Action::getActions($job_id);
}

$battle_data["player"] = Party::getParty($_SESSION["user_id"], $battle->party_id);

$player_color = "rgb(" . random_int(0,255) . "," . random_int(0,255) . "," . random_int(0,255) . ")";

$battle_data["player"]->units = Unit::getUnitsByPartyId($battle_data["player"]->party_id);
$battle_data["player"]->color = $player_color;
$battle_data["player"]->starting_point = ["x" => $battle_data["map"]["width"] - 1, "y" => $battle_data["map"]["height"] - 1];

foreach ($battle_data["player"]->units as &$unit) {
    $unit->job = $battle_data["jobs"][$unit->job_id];
    $unit->color = $player_color;
    unset($unit);
}

$enemy_color = "rgb(" . random_int(0,255) . "," . random_int(0,255) . "," . random_int(0,255) . ")";

$battle_data["enemy"] = [
    "party_id" => "cpu",
    "units" => [],
    "color" => $enemy_color,
    "starting_point" => ["x" => 0, "y" => 0]
];

$count = random_int(1, 10);

for ($i=0; $i<$count; $i++) {
    $unit = Unit::generateUnit("cpu");
    $unit->unit_id = uniqid();
    $unit->job = $battle_data["jobs"][$unit->job_id];
    $unit->color = $enemy_color;
    $battle_data["enemy"]["units"][] = $unit;
}

echo json_encode($battle_data);