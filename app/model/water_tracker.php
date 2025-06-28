<?php
require_once __DIR__ . '/../../database/db.php';

function logWaterIntake($amount = 250) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO water_logs (amount) VALUES (?)");
    $stmt->execute([$amount]);
}

function getTodayWaterIntake() {
    global $pdo;
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM water_logs WHERE DATE(log_time) = CURDATE()");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function getTodayGoal() {
    global $pdo;
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT daily_goal FROM daily_goals WHERE goal_date = ?");
    $stmt->execute([$today]);
    $goal = $stmt->fetch(PDO::FETCH_ASSOC)['daily_goal'] ?? null;

    if ($goal === null) {
        $default = 2000;
        $insert = $pdo->prepare("INSERT INTO daily_goals (goal_date, daily_goal) VALUES (?, ?)");
        $insert->execute([$today, $default]);
        return $default;
    }

    return $goal;
}

function updateTodayGoal($goal) {
    global $pdo;
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("UPDATE daily_goals SET daily_goal = ? WHERE goal_date = ?");
    $stmt->execute([$goal, $today]);
}

function getWaterHistory() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM water_logs ORDER BY log_time DESC LIMIT 20");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
