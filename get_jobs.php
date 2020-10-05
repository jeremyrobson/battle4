<?php

require_once("Job.php");

$jobs = Job::getJobs();

echo json_encode($jobs);