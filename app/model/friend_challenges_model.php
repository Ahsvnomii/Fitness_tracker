<?php
require_once __DIR__ . '/../../database/db.php';

function getAllChallenges() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM challenges ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function userAcceptedChallenge($user_id, $challenge_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM challenge_participants WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$user_id, $challenge_id]);
    return $stmt->rowCount() > 0;
}

function acceptChallenge($user_id, $challenge_id) {
    global $pdo;
    if (!userAcceptedChallenge($user_id, $challenge_id)) {
        $stmt = $pdo->prepare("INSERT INTO challenge_participants (user_id, challenge_id, progress, last_completed) VALUES (?, ?, 0, NULL)");
        $stmt->execute([$user_id, $challenge_id]);
    }
}

function incrementProgress($user_id, $challenge_id) {
    global $pdo;

    $today = date('Y-m-d');

    // Check if already marked today
    $stmt = $pdo->prepare("SELECT last_completed FROM challenge_participants WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$user_id, $challenge_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return "You haven't accepted this challenge.";
    }

    if ($row['last_completed'] === $today) {
        return "You've already marked today's progress.";
    }

    // Update progress and last_completed date
    $stmt = $pdo->prepare("UPDATE challenge_participants SET progress = progress + 1, last_completed = ? WHERE user_id = ? AND challenge_id = ?");
    $stmt->execute([$today, $user_id, $challenge_id]);

    return "Progress marked for today!";
}

function getLeaderboard($challenge_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT cp.user_id, u.name, cp.progress
        FROM challenge_participants cp
        JOIN users u ON u.id = cp.user_id
        WHERE cp.challenge_id = ?
        ORDER BY cp.progress DESC, u.name ASC
    ");
    $stmt->execute([$challenge_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sendCheer($sender_id, $receiver_id, $message) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO cheers (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$sender_id, $receiver_id, $message]);
}

function getCheersForUser($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.*, u.name AS sender_name
        FROM cheers c
        JOIN users u ON c.sender_id = u.id
        WHERE c.receiver_id = ?
        ORDER BY c.sent_at DESC
        LIMIT 20
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function createChallenge($title, $goal, $type) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO challenges (title, goal, type, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$title, $goal, $type]);
}
