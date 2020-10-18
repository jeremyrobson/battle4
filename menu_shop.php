<?php

require_once("constants.php");
require_once("Item.php");

/**
 * @param string $job_key
 * @param string $type_key
 * @param string $class_key
 * @return Item
 * @throws Exception
 */
function generate_random_item($job_key = null, $type_key = null, $class_key = null) {
    if (empty($job_key)) {
        $job_key = array_rand($GLOBALS["job_definitions"]);
    }

    if (empty($type_key)) {
        $item_types = $GLOBALS["job_definitions"][$job_key]["item_types"];
        $type_index = array_rand($item_types);
        $type_key = $item_types[$type_index];
    }

    $item_definition = $GLOBALS["item_definitions"][$type_key];

    $slot_key = $item_definition["slot"];
    $slot_id = $GLOBALS["slot_definitions"][$slot_key];

    $item = new Item([
        "slot_id" => $slot_id,
        "name" => $type_key,
        "type" => $type_key,
        "class" => $item_definition["class"],
        "cost" => random_int(100, 1000)
    ]);

    $stats = $GLOBALS["item_definitions"][$type_key]["stats"];
    $values = [];
    foreach ($stats as $stat) {
        $value = random_int(1, 5);
        $item->$stat += $value;
        $values[] = "$stat +$value";
    }

    $item->name = "$type_key " . implode(", ", $values);

    $item->save();

    return $item;
}

$items = Item::getAllItems();

while (count($items) < 10) {
    $items[] = generate_random_item();
}

?>


<div>

    <table>

        <thead>
            <tr>
                <th>Buy Item</th>
                <th>Name</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td>
                    <a href="?page=buy_item&item_code=<?=$item->item_code?>">
                        <?=$item->item_code?>
                    </a>
                </td>
                <td>
                    <?=$item->name?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>
