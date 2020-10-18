<div style="flex: 1 1 150px;">
    <table>
        <tr>
            <th>Name</th>
            <td>
                <a href="index.php?page=menu_party&party_id=<?=$party->party_id?>">
                    <?=$party->name?>
                </a>
            </td>
        </tr>
    </table>
    <a href="?page=menu_battles&party_id=<?=$party->party_id?>">View Battle Log</a>
</div>
