<?php

session_start();

require_once("Team.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$job_templates = [
    "fighter" => [
        "sprite" => "F",
        "move_cost" => 20,
        "actions" => ["melee"]
    ],
    "archer" => [
        "sprite" => "A",
        "move_cost" => 40,
        "actions" => ["arrow"]
    ],
    "wizard" => [
        "sprite" => "W",
        "move_cost" => 40,
        "actions" => ["fire"]
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RPG</title>
</head>
<body>

<?php

$page = $_GET["page"] ?? "unit_menu";

require_once("$page.php");

?>



</body>


</html>