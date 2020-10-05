<script>
    const user_id = "<?=$_SESSION['user_id']?>";

    const TILE_WIDTH = 40;
    const TILE_HEIGHT = 30;
    const RANGE_WEIGHT = 2; //how valuable range is over damage

    let action_templates = {};
    let job_templates = {};
    let player = null;
    let cpu = null;
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

    //todo: get party instead of just units which includes name, color, etc.
    function start() {
        game_state = new Battle(16, 16, player, cpu, done);
        update();
    }

    function done() {
        console.log("redirect");
    }

    window.onload = function() {
        getData("get_jobs.php")
            .then(jobs => {
                job_templates = jobs;
                return getData("get_party.php");
            })
            .then(party => {
                player = new Party(party.party_id, party.name, random_color(50, 200, 0, 100, 50, 200));
                return getData(`get_units.php?party_id=${party.party_id}`);
            })
            .then(units => {
                units.forEach(function(unit_data) {
                    player.add(new BattleUnit(player, unit_data));
                });
                return getData("get_enemy.php");
            })
            .then(units => {
                cpu = new Party("cpu", "cpu", random_color(50, 200, 0, 100, 50, 200));
                units.forEach(function(unit_data) {
                    cpu.add(new BattleUnit(cpu, unit_data));
                });
                start();
            });
    };
</script>

<script>
    const getData = (endpoint) => {
        return new Promise((resolve, reject) => {
            let request = new XMLHttpRequest();
            request.open('GET', endpoint, true);

            request.onload = function() {
                if (this.status >= 200 && this.status < 400) {
                    resolve(JSON.parse(this.response));
                } else {
                    reject();
                }
            };

            request.onerror = function() {
                // There was a connection error of some sort
            };

            request.send();
        });
    };
</script>
