<?php
require_once __DIR__ . '/../../database/db.php';

function getAllChallenges() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM challenges ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLeaderboard($challenge_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT u.name, cp.progress FROM challenge_participants cp
                           JOIN users u ON cp.user_id = u.id
                           WHERE cp.challenge_id = ?
                           ORDER BY cp.progress DESC");
    $stmt->execute([$challenge_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createChallenge($title, $goal, $type) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO challenges (title, goal, type) VALUES (?, ?, ?)");
    $stmt->execute([$title, $goal, $type]);
}

function sendCheer($sender_id, $receiver_id, $challenge_id, $message) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO cheers (sender_id, receiver_id, challenge_id, message)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$sender_id, $receiver_id, $challenge_id, $message]);
}


function getUsersExcept($exclude_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id != ?");
    $stmt->execute([$exclude_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getReceivedCheers($user_id, $challenge_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.message, u.name AS sender_name, c.sent_at
        FROM cheers c
        JOIN users u ON u.id = c.sender_id
        WHERE c.receiver_id = ? AND c.challenge_id = ?
        ORDER BY c.sent_at DESC
    ");
    $stmt->execute([$user_id, $challenge_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
