<?php
require_once __DIR__ . '/../model/friend_challenges_model.php';

session_start();
$current_user_id = $_SESSION['user_id'] ?? 1; // fallback for testing
$user_name = $_SESSION['name'] ?? 'Guest';

$challenges = getAllChallenges();
$leaderboard = [];
$cheers = [];
$msg = '';
$selectedChallenge = $_POST['challenge_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create a new challenge
    if (isset($_POST['create_challenge'])) {
        createChallenge($_POST['title'], $_POST['goal'], $_POST['type']);
        $challenges = getAllChallenges();
        $msg = "✅ Challenge created!";
    }

    // View leaderboard for a specific challenge
    if (isset($_POST['view_leaderboard'])) {
        $selectedChallenge = $_POST['challenge_id'];
        $leaderboard = getLeaderboard($selectedChallenge);
        $cheers = getReceivedCheers($current_user_id, $selectedChallenge);
    }

    // Send a cheer to another user for a specific challenge
    if (isset($_POST['send_cheer'])) {
        $challenge_id = $_POST['challenge_id'];
        $receiver_id = $_POST['receiver_id'];
        $message = $_POST['message'];

        sendCheer($current_user_id, $receiver_id, $challenge_id, $message);
        $msg = "🎉 Cheer sent!";
        
        // Refresh leaderboard and cheers after sending cheer
        $leaderboard = getLeaderboard($challenge_id);
        $cheers = getReceivedCheers($current_user_id, $challenge_id);
        $selectedChallenge = $challenge_id;
    }
}
