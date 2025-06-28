<?php
require_once __DIR__ . '/../../database/db.php';

function saveWorkoutSession($exercise, $sets, $reps, $weight, $note, $duration) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO workout_sessions (exercise, sets, reps, weight, note, duration, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$exercise, $sets, $reps, $weight, $note, $duration]);
}

function getAllWorkoutSessions() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM workout_sessions ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
