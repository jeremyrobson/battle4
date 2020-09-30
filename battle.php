<script>

    let teams = {};

    <?php
        $form = json_encode($_POST["form"]);
        echo "teams = $form;";
    ?>

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

    function done() {
        console.log("redirect");
    }
</script>

<script>
    let parties = [];

    for (const team of Object.keys(teams)) {
        let party = new Party(team, random_color(0,100));
        for (const job of Object.keys(teams[team])) {
            let count = parseInt(teams[team][job]);
            for (let i=0; i<count; i++) {
                party.add(new BattleUnit(party, job));
            }
        }
        parties.push(party);
    }

    game_state = new Battle(16, 16, parties, done);
    update();
</script>
