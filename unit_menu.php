<?php

    $jobs = Job::getJobs();

    echo "<pre>";
    print_r($jobs); die;

    $team = Team::load();

    if (empty($team->units)) {
        while (count($team->units) === 0) {
            foreach ($job_templates as $job_class => $job_template) {
                $count = random_int(1, 3);
                for ($i = 0; $i < $count; $i++) {
                    $team->addUnit(new Unit($team->name, $job_class));
                }
            }
        }
        $team->save();
    }

    ?>

<div style="display: grid; grid-template-columns: minmax(150px, 15%) 1fr;">

    <div>
        <h2>
            Unit Menu
        </h2>

        <ul>
            <li>
                <a href="index.php?page=battle">Start</a>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>

    </div>

    <div style="display: flex;">
        <?php foreach ($team->units as $unit) {
            require("unit_display.php");
        } ?>
    </div>

</div>