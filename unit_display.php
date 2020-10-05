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
            <th>AGL</th>
            <td>
                <input type="hidden" name="unit[agl]" value="<?=$unit->agl?>" />
                <?=$unit->agl?>
            </td>
        </tr>
        <tr>
            <th>STA</th>
            <td>
                <input type="hidden" name="unit[sta]" value="<?=$unit->sta?>" />
                <?=$unit->sta?>
            </td>
        </tr>
    </table>
</div>