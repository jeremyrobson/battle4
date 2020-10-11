<?php

session_start();

require_once("Job.php");
require_once("Action.php");
require_once("Party.php");
require_once("Unit.php");

$battle = [];

$battle["jobs"] = Job::getJobs();

foreach ($battle["jobs"] as $job_id => &$job) {
    $job->actions = Action::getActions($job_id);
}

$parties = Party::getParties($_SESSION["user_id"]);

$battle["player"] = reset($parties);

$battle["player"]->units = Unit::getUnits($battle["player"]->party_id);

foreach ($battle["player"]->units as &$unit) {
    $unit->job = $battle["jobs"][$unit->job_id];
    unset($unit);
}

$battle["enemy"] = [
    "party_id" => "cpu",
    "units" => [],
];

$count = random_int(1, 10);

for ($i=0; $i<$count; $i++) {
    $unit = Unit::generateUnit("cpu");
    $unit->job = $battle["jobs"][$unit->job_id];
    $battle["enemy"]["units"][] = $unit;
}

echo json_encode($battle);