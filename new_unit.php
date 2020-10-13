<?php
    require_once("User.php");

    $user = User::getUserByUserId($_SESSION["user_id"]);

    $party_id = $_GET["party_id"];

    if (isset($_POST["unit"])) {
        $unit = new Unit($_POST["unit"]);
        $cost = intval($_POST["unit"]["cost"]);

        try {
            $result = $user->hireUnit($party_id, $unit, $cost);
        }
        catch (Exception $e) {
            echo "<h1>" . $e->getMessage() . "</h1>";
        }
    }
    else {
        $unit = Unit::generateUnit($party_id);
        $cost = random_int(50, 250);
    }
?>

<h2>Unit Academy</h2>

<label for="funds"></label>
<div id="funds">Available Funds: <?=$user->funds;?></div>

<form action="index.php?page=new_unit&party_id=<?=$party_id?>" method="post">

    <h3>Hire this unit?</h3>

    <div style="flex: 1 1 150px;">
        <table>
            <tr>
                <th>Name</th>
                <td>
                    <input type="hidden" name="unit[name]" value="<?=$unit->name?>" />
                    <?=$unit->name?>
                </td>
            </tr>
            <tr>
                <th>Job</th>
                <td>
                    <input type="hidden" name="unit[job_id]" value="<?=$unit->job_id?>" />
                    <?=Job::getJob($unit->job_id)->name?>
                </td>
            </tr>
            <tr>
                <th>HP</th>
                <td>
                    <input type="hidden" name="unit[hp]" value="<?=$unit->hp?>" />
                    <?=$unit->hp?>
                </td>
            </tr>
            <tr>
                <th>MP</th>
                <td>
                    <input type="hidden" name="unit[mp]" value="<?=$unit->mp?>" />
                    <?=$unit->mp?>
                </td>
            </tr>
            <tr>
                <th>STR</th>
                <td>
                    <input type="hidden" name="unit[str]" value="<?=$unit->str?>" />
                    <?=$unit->str?>
                </td>
            </tr>
            <tr>
                <th>AGL</th>
                <td>
                    <input type="hidden" name="unit[agl]" value="<?=$unit->agl?>" />
                    <?=$unit->agl?>
                </td>
            </tr>
            <tr>
                <th>MAG</th>
                <td>
                    <input type="hidden" name="unit[mag]" value="<?=$unit->mag?>" />
                    <?=$unit->mag?>
                </td>
            </tr>
            <tr>
                <th>STA</th>
                <td>
                    <input type="hidden" name="unit[sta]" value="<?=$unit->sta?>" />
                    <?=$unit->sta?>
                </td>
            </tr>
            <tr>
                <th>Cost</th>
                <td>
                    <input type="hidden" name="unit[cost]" value="<?=$cost?>" />
                    <?=$cost?>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <a href="index.php?page=new_unit&party_id=<?=$party_id?>">Pass</a>
        <button type="submit">Hire</button>
    </div>
</form>
