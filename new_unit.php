<?php
    $party_id = $_GET["party_id"];

    if (isset($_POST["unit"])) {
        $unit_id = Unit::insertUnit($_SESSION["user_id"], $party_id, $_POST["unit"]);
        if ($unit_id) {
            header("Location: index.php?page=menu_party&party_id=$party_id");
        }
        else {
            echo "<h1>ERROR: COULD NOT INSERT UNIT</h1>";
        }
    }

    $unit = Unit::generateUnit($party_id);

?>

<form action="index.php?page=new_unit&party_id=<?=$party_id?>" method="post">

    <h2>Hire this unit?</h2>

    <?php include("unit_display.php"); ?>

    <div>
        <button type="submit">Select</button>
    </div>
</form>
