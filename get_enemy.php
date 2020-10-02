<?php

require_once("Unit.php");

$units = [];

$count = random_int(1, 10);

for ($i=0; $i<$count; $i++) {
    $units[] = new Unit("cpu", "fighter");
}

echo json_encode($units);