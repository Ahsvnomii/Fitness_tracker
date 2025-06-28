<?php
require_once __DIR__ . '/../model/water_tracker.php';

$waterIntake = getTodayWaterIntake();
$dailyGoal = getTodayGoal();
$logs = getWaterHistory();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['log_cup'])) {
        logWaterIntake();
        header("Location: water_tracker.php");
        exit;
    }

    if (isset($_POST['update_goal'])) {
        $goal = (int)$_POST['daily_goal'];
        updateTodayGoal($goal);
        $msg = "Goal updated!";
    }

    $dailyGoal = getTodayGoal(); // reload after update
    $waterIntake = getTodayWaterIntake(); // update intake after new goal
    $logs = getWaterHistory();
}
