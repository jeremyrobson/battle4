<?php

session_start();

require_once("Party.php");

$parties = Party::getParties($_SESSION["user_id"]);
$party = reset($parties);

echo json_encode($party);