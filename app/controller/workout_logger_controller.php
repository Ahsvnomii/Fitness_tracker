<?php
require_once __DIR__ . '/../model/workout_model.php';

$msg = '';
$sessions = getAllWorkoutSessions();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $exercise = $_POST['exercise'] ?? '';
    $sets = $_POST['sets'] ?? '';
    $reps = $_POST['reps'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $note = $_POST['note'] ?? '';
    $duration = $_POST['duration'] ?? '00:00:00';

    saveWorkoutSession($exercise, $sets, $reps, $weight, $note, $duration);
    $msg = "Workout saved!";
}
