<?php

session_start();

if (!$_SESSION["username"]) {
    header("Location: login.php");
}

require_once("Team.php");
require_once("Job.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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