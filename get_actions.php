<?php

require_once("Action.php");

$actions = Action::getActions("%");

echo json_encode($actions);