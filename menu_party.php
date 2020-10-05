<?php

    $party_id = $_GET["party_id"];
    $party = Party::getParty($_SESSION["user_id"], $party_id);

?>

<h2>
    <?=$party->name?>
</h2>

<div style="display: grid; grid-template-columns: minmax(150px, 15%) 1fr;">

    <div style="display: flex;">
        <?php foreach ($party->units as $unit) {
            require("unit_display.php");
        } ?>
    </div>

</div>

<div>
    <a href="index.php?page=new_unit&party_id=<?=$party_id?>">New Unit</a>
</div>