<?php

session_start();

require_once("Job.php");
require_once("Action.php");
require_once("Party.php");
require_once("Unit.php");

$battle = [];

$battle["map"]["width"] = 16;
$battle["map"]["height"] = 16;

$battle["jobs"] = Job::getJobs();

foreach ($battle["jobs"] as $job_id => &$job) {
    $job->actions = Action::getActions($job_id);
}

$parties = Party::getParties($_SESSION["user_id"]);

$battle["player"] = reset($parties);

$player_color = "rgb(" . random_int(0,255) . "," . random_int(0,255) . "," . random_int(0,255) . ")";

$battle["player"]->units = Unit::getUnits($battle["player"]->party_id);
$battle["player"]->color = $player_color;
$battle["player"]->starting_point = ["x" => $battle["map"]["width"] - 1, "y" => $battle["map"]["height"] - 1];

foreach ($battle["player"]->units as &$unit) {
    $unit->job = $battle["jobs"][$unit->job_id];
    $unit->color = $player_color;
    unset($unit);
}

$enemy_color = "rgb(" . random_int(0,255) . "," . random_int(0,255) . "," . random_int(0,255) . ")";

$battle["enemy"] = [
    "party_id" => "cpu",
    "units" => [],
    "color" => $enemy_color,
    "starting_point" => ["x" => 0, "y" => 0]
];

$count = random_int(1, 10);

for ($i=0; $i<$count; $i++) {
    $unit = Unit::generateUnit("cpu");
    $unit->unit_id = uniqid();
    $unit->job = $battle["jobs"][$unit->job_id];
    $unit->color = $enemy_color;
    $battle["enemy"]["units"][] = $unit;
}

echo json_encode($battle);