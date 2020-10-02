<?php

session_start();

require_once("Team.php");

$team = Team::load();

echo json_encode($team->units);