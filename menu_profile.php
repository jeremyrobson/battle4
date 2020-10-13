<?php

    require_once("User.php");

    $user = User::getUser("user_id", $_SESSION["user_id"]);

    ?>

<div>

    <h2>User Profile</h2>

    <table>
        <tr>
            <th>Username</th>
            <td><?=$user->username?></td>
        </tr>
        <tr>
            <th>Funds</th>
            <td><?=$user->funds?></td>
        </tr>
    </table>

</div>