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

$parties = [
    "home" => [
        "id" => "home_id",
        "starting_point" => ["x" => $battle_data["map"]["width"] - 1, "y" => $battle_data["map"]["height"] - 1]
    ],
    "away" => [
        "id" => "away_id",
        "starting_point" => ["x" => 0, "y" => 0]
    ]
];

foreach ($parties as $key => $party) {

    $id = $party["id"];

    $battle_data[$key] = Party::getParty($battle->$id);

    $color = "rgb(" . random_int(0, 255) . "," . random_int(0, 255) . "," . random_int(0, 255) . ")";

    $battle_data[$key]->units = Unit::getUnitsByPartyId($battle_data[$key]->party_id);
    $battle_data[$key]->color = $color;
    $battle_data[$key]->starting_point = $parties[$key]["starting_point"];

    foreach ($battle_data[$key]->units as &$unit) {
        $unit->job = $battle_data["jobs"][$unit->job_id];
        $unit->color = $color;
        unset($unit);
    }
}

echo json_encode($battle_data);