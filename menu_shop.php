<?php

require_once("constants.php");
require_once("functions.php");
require_once("User.php");
require_once("Item.php");

$user = User::getUserByUserId($_SESSION["user_id"]);

$items = Item::getShopItems();

$available_items = array_filter($items, function($item) {
    return !isset($item["party_id"]);
});

$count = 10 - count($available_items);

for ($i=0; $i<$count; $i++) {
    $items[] = (array) generate_random_item();
}

?>

<h2>Ye Olde Shoppe</h2>

<div>
    Funds: <?=$user->funds?>
</div>

<div>

    <h3>Catalogue</h3>

    <table>

        <thead>
            <tr>
                <th>Name</th>
                <th>Cost</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <?php
                        if (isset($item["party_id"])):
                            echo $item["name"];
                        else:
                    ?>
                    <a href="?page=buy_item&item_code=<?=$item["item_code"]?>">
                        <?=$item["name"]?>
                    </a>
                    <?php endif; ?>
                </td>
                <td>
                    $<?=$item["cost"]?>
                </td>
                <td>
                    <?php
                    if (isset($item["party_id"])):
                        echo "Purchased";
                    else:
                        echo "Available";
                    endif;
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>
