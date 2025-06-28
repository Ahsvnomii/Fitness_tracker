<?php
require_once __DIR__ . '/../model/friend_challenges_model.php';

session_start();
$current_user_id = $_SESSION['user_id'] ?? 1;

$msg = '';
$selectedChallenge = $_POST['challenge_id'] ?? $_GET['challenge_id'] ?? '';

// Handle POST requests with redirect to avoid form resubmission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_challenge'])) {
        createChallenge(trim($_POST['title']), (int)$_POST['goal'], trim($_POST['type']));
        header("Location: friend_challenges.php?msg=" . urlencode("Challenge created!"));
        exit;
    }

    if (isset($_POST['accept_challenge'])) {
        acceptChallenge($current_user_id, $_POST['challenge_id']);
        header("Location: friend_challenges.php?msg=" . urlencode("Challenge accepted!"));
        exit;
    }

    if (isset($_POST['mark_done'])) {
        $msg = incrementProgress($current_user_id, $_POST['challenge_id']);
        header("Location: friend_challenges.php?challenge_id=" . (int)$_POST['challenge_id'] . "&msg=" . urlencode($msg));
        exit;
    }

    if (isset($_POST['send_cheer'])) {
        $message = trim($_POST['message']);
        if ($message !== '') {
            sendCheer($current_user_id, $_POST['receiver_id'], $message);
            header("Location: friend_challenges.php?msg=" . urlencode("Cheer sent!"));
            exit;
        } else {
            $msg = "Please enter a cheer message.";
        }
    }
}

// For GET requests and after redirects
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

$challenges = getAllChallenges();
$leaderboard = [];
if ($selectedChallenge) {
    $leaderboard = getLeaderboard($selectedChallenge);
}
$cheers = getCheersForUser($current_user_id);
