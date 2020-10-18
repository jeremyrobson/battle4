<?php

session_start();

if (!isset($_SESSION["username"])) {
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
                <a href="index.php?page=battle_start">Start Battle</a>
            </li>
            <li>
                <a href="index.php?page=menu_profile">View Profile</a>
            </li>
            <li>
                <a href="index.php?page=menu_parties">View Parties</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>

    </div>

    <div>

        <?php
            if (isset($_SESSION["messages"]) && is_array($_SESSION["messages"])) {
                while ($message = array_shift($_SESSION["messages"])) {
                    echo "<h1>$message</h1>";
                }
            }
        ?>

        <?php

        $page = $_GET["page"] ?? "menu_profile";

        require_once("$page.php");

        ?>

    </div>

</body>


</html>