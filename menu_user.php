<?php

$parties = Party::getParties($_SESSION["user_id"]);

?>

<h2>Parties</h2>

<div style="display: grid; grid-template-columns: minmax(150px, 15%) 1fr;">

    <div style="display: flex;">
        <?php foreach ($parties as $party) {
            require("party_display.php");
        } ?>
    </div>

</div>

<div>
    <a href="index.php?page=new_party">New Party</a>
</div>
