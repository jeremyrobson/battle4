<?php

$jobs = Job::getJobs();

$battle_results = json_decode($_POST["battle_results"], true);

$player = $battle_results["player"];
$enemy = $battle_results["enemy"];
?>

<h2>Battle Results</h2>

<h3>Player</h3>

<div style="display:flex;">

<?php
    foreach ($player["units"] as $u):
        $unit = Unit::getUnit($u["unit_id"]);
        $unit->hp = $u["hp"];
        $unit->mp = $u["mp"];
        $unit->sta = $u["sta"];
        require("unit_display.php");
    endforeach;
    ?>

</div>

<h3>Enemies</h3>

<div style="display:flex;">

    <?php
    foreach ($enemy["units"] as $u):
        $unit = new Unit($u);
        $unit->hp = $u["hp"];
        $unit->mp = $u["mp"];
        $unit->sta = $u["sta"];
        require("unit_display.php");
    endforeach;
    ?>

</div>