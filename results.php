<?php

require_once("User.php");
require_once("Battle.php");
require_once("Spoils.php");

function format_gain($value) {
    return !empty($value) ? "+$value" : '';
}

$jobs = Job::getJobs();

$battle_results = json_decode($_POST["battle_results"], true);
$battle = Battle::getBattleByCode($battle_results["battle_code"]);

$winner = Party::getParty($battle_results["winner"]);
$loser = Party::getParty($battle_results["loser"]);

$winner_units = Unit::getUnitsByPartyId($winner->party_id);
$loser_units = Unit::getUnitsByPartyId($loser->party_id);

if ($winner->user_id != $_SESSION["user_id"]) {
    $result = "Total Defeat";
    $battle->winner_id = $winner->party_id;
    $battle->save();
}
else {
    $result = "Decisive Victory";

    $unit_gains = [];

    if (empty($battle->winner_id)) {
        $battle->winner_id = $winner->party_id;
        $battle->funds = count($loser_units) * random_int(1, 25);
        $battle->points = count($loser_units);
        $battle->save();

        $user = User::getUserByUserId($winner->user_id);
        $user->funds += $battle->funds;
        $user->save();

        $stats = ["str", "agl", "mag"];
        for ($i = 0; $i < $battle->points; $i++) {
            $stat = $stats[array_rand($stats)];
            $unit_id = array_rand($winner_units);

            $spoils = new Spoils([
                "battle_id" => $battle->battle_id,
                "type" => $stat,
                "user_id" => $winner->user_id,
                "party_id" => $winner->party_id,
                "unit_id" => $unit_id,
                "value" => 1,
                "item_id" => null,
                "applied" => true
            ]);

            $spoils->save();

            if (isset($unit_gains[$unit_id][$stat])) {
                $unit_gains[$unit_id][$stat] += 1;
            } else {
                $unit_gains[$unit_id][$stat] = 1;
            }
        }

        foreach ($winner_units as $unit_id => $u) {
            if (!isset($unit_gains[$unit_id])) {
                continue;
            }
            $unit = Unit::getUnit($unit_id);
            foreach ($unit_gains[$unit_id] as $stat => $gain) {
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
    foreach ($winner_units as $unit_id => $unit):
        require("unit_gains_display.php");
    endforeach;
    ?>

</div>

<h3>Enemies</h3>

<div style="display:flex;">

    <?php
    foreach ($loser_units as $unit_id => $unit):
        require("unit_display.php");
    endforeach;
    ?>

</div>