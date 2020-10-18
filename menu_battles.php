<?php

require_once("Battle.php");

$party_id = $_GET["party_id"];

$battles = Battle::getBattles("party_id", $party_id);

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
                    <?=$battle->winner?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
