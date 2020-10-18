<?php
    require_once("Battle.php");
    $parties = Party::getParties($_SESSION['user_id']);
    $party = reset($parties);
    $battle = new Battle([
            "party_id" => $party->party_id
    ]);
    $battle->save();
?>

<script>
    const user_id = "<?=$_SESSION['user_id']?>";
    const battle_code = "<?=$battle->battle_code?>";

    const TILE_WIDTH = 40;
    const TILE_HEIGHT = 30;
    const RANGE_WEIGHT = 2; //how valuable range is over damage

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
    let paused = false;

    function addSprite(sprite) {
        sprites.push(sprite);
    }

    function update(timestamp) {
        if (game_state.status !== "done" && !paused) {
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
        console.log("Starting Battle...");
        game_state = new Battle({
            width: 16,
            height: 16,
            player: player,
            cpu: cpu,
            battle_code: battle_code
        },done);
        update();
    }

    function pause() {
        paused = true;
    }

    function load(battle_data) {
        console.log(battle_data);

        player = new Party(battle_data["player"]);
        cpu = new Party(battle_data["enemy"]);

        start();
    }

    function done() {
        let battle_data = game_state.getBattleData();
        let br = document.getElementById("battle_results");
        br.value = JSON.stringify(battle_data);
        document.getElementById("battle_results_form").submit();
    }

    window.onload = function() {
        fetch(`get_battle_data.php?battle_code=${battle_code}`)
            .then((response) => {
                response.json().then(function(battle_data) {
                    load(battle_data);
                });
            });
    };
</script>
