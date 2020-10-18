<?php

$GLOBALS["job_definitions"] = [
    "fighter" => [
        "actions" => ["melee"],
        "item_types" => ["sword", "shield", "hat", "helmet", "armor", "ring"]
    ],
    "archer" => [
        "actions" => ["arrow"],
        "item_types" => ["bow", "hat", "helmet", "armor", "ring"]
    ],
    "wizard" => [
        "actions" => ["fire"],
        "item_types" => ["staff", "hat", "robe", "ring"]
    ]
];

$GLOBALS["action_definitions"] = [
    "melee" => [
        "range" => 1.50,
        "spread" => 0.00,
        "action_cost" => 50
    ],
    "arrow" => [
        "range" => 5.00,
        "spread" => 0.00,
        "action_cost" => 50
    ],
    "fire" => [
        "range" => 3.00,
        "spread" => 1.50,
        "action_cost" => 50
    ]
];

$GLOBALS["slot_definitions"] = [
    "right" => 1,
    "left" => 2,
    "head" => 3,
    "body" => 4,
    "accessory" => 5
];

$GLOBALS["item_definitions"] = [
    "potion" => [
        "class" => "usable",
        "stats" => [
            "hp", "mp"
        ]
    ],
    "sword" => [
        "class" => "weapon",
        "slot" => "right",
        "stats" => ["pwr"],
        "jobs" => ["Fighter"]
    ],
    "bow" => [
        "class" => "weapon",
        "slot" => "right",
        "stats" => ["pwr"],
        "jobs" => ["Archer"]
    ],
    "staff" => [
        "class" => "weapon",
        "slot" => "right",
        "stats" => ["pwr", "mag"],
        "jobs" => ["Wizard"]
    ],
    "shield" => [
        "class" => "shield",
        "slot" => "left",
        "stats" => ["def"],
        "jobs" => ["Fighter"]
    ],
    "hat" => [
        "class" => "helmet",
        "slot" => "head",
        "stats" => ["def"],
        "jobs" => ["Fighter", "Archer", "Wizard"]
    ],
    "helmet" => [
        "class" => "helmet",
        "slot" => "head",
        "stats" => ["def"],
        "jobs" => ["Fighter", "Archer"]
    ],
    "armor" => [
        "class" => "armor",
        "slot" => "body",
        "stats" => ["def"],
        "jobs" => ["Fighter", "Archer"]
    ],
    "robe" => [
        "class" => "armor",
        "slot" => "body",
        "stats" => ["def"],
        "jobs" => ["Wizard"]
    ],
    "ring" => [
        "class" => "ring",
        "slot" => "accessory",
        "stats" => [],
        "bonuses" => ["pwr", "def", "hp", "mp", "str", "agl", "sta", "mag"]
    ]
];
