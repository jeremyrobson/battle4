<?php

require_once("Battle.php");
require_once("Party.php");

$party_id = $_GET["party_id"];

$home_battles = Battle::getBattles("home_id", $party_id);
$away_battles = Battle::getBattles("away_id", $party_id);
$battles = array_merge($home_battles, $away_battles);

?>

<h2>Battles</h2>

<div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Winner</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($battles as $battle): ?>
            <tr>
                <td>
                    <a href="?page=battle_view&battle_code=<?=$battle->battle_code?>">
                        <?=$battle->battle_code?>
                    </a>
                </td>
                <td>
                    <?=Party::getParty($battle->winner_id)->name?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
