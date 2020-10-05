<?php

    if (isset($_POST["party"])) {
        $party_id = Party::insertParty($_SESSION["user_id"], $_POST["party"]);
        header("Location: index.php?page=menu_party&party_id=$party_id");
    }

?>

<form action="index.php?page=new_party" method="POST">
    <h2>New Party</h2>
    <div>
        <label for="party_name">Party Name</label>
        <input type="text" name="party[name]" id="party_name" required />
    </div>
    <div>
        <button type="submit">Submit</button>
    </div>
</form>
