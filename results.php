<?php

require_once("User.php");
require_once("Battle.php");
require_once("Spoils.php");

function format_gain($value) {
    return !empty($value) ? "+$value" : '';
}

$jobs = Job::getJobs();

$battle_results = json_decode($_POST["battle_results"], true);

$player = $battle_results["player"];
$enemy = $battle_results["enemy"];
$battle = Battle::getBattleByCode($battle_results["battle_code"]);
$enemy_count = count($enemy["units"]);

if ($battle_results["winner"] === "enemy") {
    $result = "Total Defeat";
    $battle->winner = "enemy";
    $battle->funds = 0;
    $battle->points = 0;
    $battle->save();
}
else {
    $result = "Decisive Victory";

    if (!$battle->winner) {
        $battle->winner = $battle_results["winner"];
        $battle->funds = $enemy_count * random_int(1, 25);
        $battle->points = count($enemy["units"]);
        $battle->save();

        $user = User::getUserByUserId($_SESSION["user_id"]);
        $user->funds += $battle->funds;
        $user->save();

        $stats = ["str", "agl", "mag"];
        for ($i = 0; $i < $battle->points; $i++) {
            $stat = $stats[array_rand($stats)];
            $unit_id = array_rand($player["units"]);

            $spoils = new Spoils([
                "battle_id" => $battle->battle_id,
                "type" => $stat,
                "user_id" => $user->user_id,
                "party_id" => $battle_results["winner"],
                "unit_id" => $unit_id,
                "value" => 1,
                "item_id" => null,
                "applied" => true
            ]);

            $spoils->save();

            if (isset($player["units"]["gains"][$stat])) {
                $player["units"][$unit_id]["gains"][$stat] += 1;
            } else {
                $player["units"][$unit_id]["gains"][$stat] = 1;
            }
        }

        foreach ($player["units"] as $unit_id => $u) {
            if (!isset($player["units"][$unit_id]["gains"])) {
                continue;
            }
            $unit = Unit::getUnit($u["unit_id"]);
            foreach ($player["units"][$unit_id]["gains"] as $stat => $gain) {
                $unit->$stat += $gain;
            }
            $unit->save();
        }
    }
}

?>

<h2>Battle Result: <?=$result?></h2>

<h3>Data</h3>

<ul>
    <li>Battle Code: <?=$battle_results["battle_code"]?></li>
    <li>Winner Party ID: <?=$battle_results["winner"]?></li>
</ul>

<h3>Spoils</h3>

<ul>
    <li>Funds: +<?=$battle->funds?></li>
    <li>Points: <?=$battle->points?></li>
</ul>

<h3>Player</h3>

<div style="display:flex;">

<?php
    $show_gains = true;
    foreach ($player["units"] as $u):
        $unit = Unit::getUnit($u["unit_id"]);
        $unit->hp = $u["hp"];
        $unit->mp = $u["mp"];
        $unit->sta = $u["sta"];
        require("unit_gains_display.php");
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