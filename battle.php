<script>
    const TILE_WIDTH = 40;
    const TILE_HEIGHT = 30;
    const RANGE_WEIGHT = 2; //how valuable range is over damage

    let action_templates = {
        "melee": {
            "range": 1.5,  //sqrt(2) is minimum required distance for melee attack
            "action_cost": 50,
            "spread": 0
        },
        "arrow": {
            "range": 5,
            "action_cost": 50,
            "spread": 0
        },
        "fire": {
            "range": 3,
            "action_cost": 50,
            "spread": 1.5
        }
    };

    let job_templates = {
        "fighter": {
            "sprite": "F",
            "move_cost": 20,
            "actions": ["melee"]
        },
        "archer": {
            "sprite": "A",
            "move_cost": 40,
            "actions": ["arrow"]
        },
        "wizard": {
            "sprite": "W",
            "move_cost": 40,
            "actions": ["fire"]
        }
    };
</script>

<?php
    $battle_scripts = [
        "functions.js",
        "Party.js",
        "Battle.js",
        "BattleAction.js",
        "BattleMap.js",
        "BattleUnit.js",
        "BattleSprite.js",
        "BattleQueue.js",
        "BattleLine.js",
        "BattleText.js",
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
        if (game_state.status !== "done") {
            game_state.update(timestamp);
        }

        textarea.innerHTML = game_state.text;

        sprites.forEach(function (sprite) {
            sprite.update();
        });

        sprites = sprites.filter(function(sprite) {
            return !sprite.dead;
        });

        draw();
        window.requestAnimationFrame(update);
    }

    function draw() {
        game_state.draw(context);

        sprites.forEach(function (sprite) {
            sprite.draw(context);
        });
    }

    //todo: get team instead of just units which includes name, color, etc.
    function start(units) {
        let parties = [];

        let player = new Party("player", random_color(50, 200, 0, 100, 50, 200));
        units.forEach(function(unit_data) {
            player.add(new BattleUnit(player, unit_data));
        });
        parties.push(player);

        let cpu = new Party("cpu", random_color(50, 200, 0, 100, 50, 200));
        for (var i=0; i<5; i++) {
            cpu.add(new BattleUnit(cpu, {
                team: "cpu",
                job_class: "fighter",
                name: "Enemy " + i,
                hp: 100,
                agl: 7
            }));
        }
        parties.push(cpu);

        game_state = new Battle(16, 16, parties, done);
        update();
    }

    function done() {
        console.log("redirect");
    }
</script>

<script>
    var request = new XMLHttpRequest();
    request.open('GET', 'get_units.php', true);

    request.onload = function() {
        if (this.status >= 200 && this.status < 400) {
            start(JSON.parse(this.response));
        } else {
            // We reached our target server, but it returned an error
        }
    };

    request.onerror = function() {
        // There was a connection error of some sort
    };

    request.send();
</script>
