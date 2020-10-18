<?php

require_once("Battle.php");
require_once("Unit.php");
require_once("Spoils.php");

$battle_code = $_GET["battle_code"];

$battle = Battle::getBattleByCode($battle_code);

$spoils = Spoils::getSpoils("battle_id", $battle->battle_id);

$units = Unit::getUnitsByPartyId($battle->party_id);

?>

<h2>Battle: <?=$battle->battle_code?></h2>

<div>
    <h3>Result:
        <?php
            if ($battle->party_id === $battle->winner) {
                echo "Decisive Victory";
            }
            else {
                echo "Total Defeat";
            }
        ?>
    </h3>
</div>

<div>
    <h3>Spoils</h3>

    <ul>
        <li>Funds: +<?=$battle->funds?></li>
        <li>Points: <?=$battle->points?></li>
    </ul>
</div>

<div>
    <h3>Stat Increases</h3>

    <table>

        <thead>
            <tr>
                <th>Unit</th>
                <th>Type</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($spoils as $spoil): ?>

            <tr>

                <td>
                    <?=$units[$spoil->unit_id]->name ?>
                </td>

                <td>
                    <?=$spoil->type?>
                </td>

                <td>
                    +<?=$spoil->value?>
                </td>

            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</div>
