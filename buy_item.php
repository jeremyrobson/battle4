<?php

require_once("User.php");
require_once("Item.php");

$user = User::getUserByUserId($_SESSION["user_id"]);

$item_code = $_GET["item_code"];

$item = Item::getItemByCode($item_code);

$parties = Party::getParties($_SESSION["user_id"]);

if (isset($_GET["party_id"])) {
    $item_party_id = Item::buyItem($item->item_id, $_GET["party_id"]);

    if ($item_party_id) {
        $user->funds -= $item->cost;
        $user->save();

        $_SESSION["messages"][] = "$item->name was bought!";
        header("Location: ?page=menu_shop");
    }
}

?>

<h2>Buy Item</h2>

<div>
    Funds: <?=$user->funds?>
</div>

<div>

    <h3>
        <?=$item->name?>
    </h3>
    <h4>
        Cost: <?=$item->cost?>
    </h4>

</div>

<div>

    <?php
    if ($user->funds > $item->cost):
    ?>

    <h4>Which party gets this item?</h4>

    <?php foreach ($parties as $party): ?>

        <div>
            <a href="?page=buy_item&item_code=<?=$item_code?>&party_id=<?=$party->party_id?>">
                <?=$party->name?>
            </a>
        </div>

    <?php endforeach; ?>

    <?php
    else:
    ?>

    <h4>You cannot afford this item</h4>

    <?php endif; ?>

</div>
