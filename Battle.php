<script>
    const TILE_WIDTH = 40;
    const TILE_HEIGHT = 30;
    const RANGE_WEIGHT = 2; //how valuable range is over damage

    let action_templates = {
        "melee": {
            "range": 1.5,  //sqrt(2) is minimum required distance for melee attack
            "action_cost": 50
        },
        "arrow": {
            "range": 5,
            "action_cost": 50
        }
    };

    let job_templates = {
        "fighter": {
            "move_cost": 20,
            "actions": ["melee"]
        },
        "archer": {
            "move_cost": 40,
            "actions": ["arrow"]
        }
    };
</script>

<?php
    $battle_scripts = [
            "phonemes.js",
        "functions.js",
        "Party.js",
        "Battle.js",
        "BattleAction.js",
        "BattleMap.js",
        "BattleUnit.js",
        "BattleSprite.js",
        "BattleQueue.js",
        "BattleLine.js",
        "BattleDamage.js"
    ];

    foreach ($battle_scripts as $script) {
        echo "<script src=\"{$script}\"></script>\n";
    }

    require_once("battle.html");
?>

<script>
    let canvas = document.getElementById("canvas");
    let context = canvas.getContext("2d");
    context.textBaseline = "top";
    let textarea = document.getElementById("textarea");
    let game_state = null;
    let sprites = [];

    function addSprite(sprite) {
        sprites.push(sprite);
    }

    function update(timestamp) {
        game_state.update(timestamp);
        textarea.innerHTML = game_state.text;

        sprites.forEach(function (sprite) {
            sprite.update();
        });

        draw();
        window.requestAnimationFrame(update);
    }

    function draw() {
        game_state.draw(context);

        sprites.forEach(function (sprite) {
            if (!sprite.dead) {
                sprite.draw(context);
            }
        });
    }
</script>

<script>
    let player = new Party("player", "rgb(0,255,255)");
    player.add(new BattleUnit(player, "fighter"));
    player.add(new BattleUnit(player, "fighter"));
    player.add(new BattleUnit(player, "fighter"));
    player.add(new BattleUnit(player, "archer"));
    player.add(new BattleUnit(player, "archer"));

    game_state = new Battle(16, 16, player);
    update();
</script>