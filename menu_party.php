<?php

    require_once("Item.php");

    $jobs = Job::getJobs();
    $party_id = $_GET["party_id"];
    $party = Party::getParty($party_id);
    $units = Unit::getUnitsByPartyId($party_id);
    $items = Item::getItemsByPartyId($party_id);
?>

<h2>
    <?=$party->name?>
</h2>

<div style="display: grid; grid-template-columns: minmax(150px, 15%) 1fr;">

    <div style="display: flex;">
        <?php foreach ($units as $unit) {
            require("unit_display.php");
        } ?>
    </div>

</div>

<div>
    <a href="index.php?page=new_unit&party_id=<?=$party_id?>">Hire Unit</a>
</div>

<div>

    <table>

        <thead>

            <tr>

                <th>Name</th>
                <th>Type</th>
                <th>Class</th>
                <th>Cost</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <?=$item->name?>
                </td>
                <td>
                    <?=$item->type?>
                </td>
                <td>
                    <?=$item->class?>
                </td>
                <td>
                    $<?=$item->cost?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>

    </table>

</div>