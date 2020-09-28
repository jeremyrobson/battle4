<?php

    $job_templates = [
        "fighter" => [
            "sprite" => "F",
            "move_cost" => 20,
            "actions" => ["melee"]
        ],
        "archer" => [
            "sprite" => "A",
            "move_cost" => 40,
            "actions" => ["arrow"]
        ],
        "wizard" => [
            "sprite" => "W",
            "move_cost" => 40,
            "actions" => ["fire"]
        ]
    ];
?>

<form action="battle.php" method="post">

    <table>
        <thead>
            <tr>
                <th>Job</th>
                <th>
                    <input type="radio" name="team" class="team" value="player" id="player" checked="checked" />
                    <label for="player">Player</label>
                </th>
                <th>
                    <input type="radio" name="team" class="team" value="cpu" id="cpu" />
                    <label for="cpu">CPU</label>
                </th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($job_templates as $job => $template):
            ?>
            <tr>
                <td>
                    <?=$job?>
                </td>
                <td id="player_<?=$job?>">
                    <input type="hidden" name="form[player][<?=$job?>]" id="form_player_<?=$job?>" value="0" />
                    <span>0</span>
                </td>
                <td id="cpu_<?=$job?>">
                    <input type="hidden" name="form[cpu][<?=$job?>]" id="form_cpu_<?=$job?>" value="0" />
                    <span>0</span>
                </td>
                <td>
                    <button type="button" class="add_button" id="add" data-dir="up" data-job="<?=$job?>">+</button>
                </td>
                <td>
                    <button type="button" class="add_button" id="subtract" data-dir="down" data-job="<?=$job?>">-</button>
                </td>
            </tr>
            <?php
                endforeach;
            ?>
        </tbody>
    </table>

    <button type="submit">Submit</button>
</form>

<script>
    var count = {
        "player": {},
        "cpu": {}
    };

    window.onload = function() {
        var buttons = document.querySelectorAll('.add_button');

        for (const button of buttons) {
            button.addEventListener("click", function(event) {
                var team = document.querySelector('input[name="team"]:checked').value;

                if (this.dataset.dir === "up") {
                    if (!count[team][this.dataset.job]) {
                        count[team][this.dataset.job] = 1;
                    } else {
                        count[team][this.dataset.job]++;
                    }
                }
                else {
                    if (!count[team][this.dataset.job]) {
                        count[team][this.dataset.job] = 0;
                    } else {
                        count[team][this.dataset.job]--;
                    }
                }
                document.querySelector("td#" + team + "_" + this.dataset.job + " > span").innerHTML = count[team][this.dataset.job];
                document.querySelector(`#form_${team}_${this.dataset.job}`).value = count[team][this.dataset.job];
            });
        }
    };
</script>
