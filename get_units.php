<?php

session_start();

require_once("Party.php");

$party = Party::getParty($_SESSION["user_id"], $_GET["party_id"]);

echo json_encode($party->units);