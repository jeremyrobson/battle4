<?php

session_start();

if (!$_SESSION["username"]) {
    header("Location: login.php");
}

require_once("DB.php");
require_once("Party.php");
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

<div style="display: grid; grid-template-columns: minmax(150px, 15%) 1fr;">

    <div>
        <h2>
            User Menu
        </h2>

        <ul>
            <li>
                <a href="index.php?page=battle">Start</a>
            </li>
            <li>
                <a href="index.php?page=menu_user">View Parties</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>

    </div>

    <div>

        <?php

        $page = $_GET["page"] ?? "menu_user";

        require_once("$page.php");

        ?>

    </div>

</body>


</html>