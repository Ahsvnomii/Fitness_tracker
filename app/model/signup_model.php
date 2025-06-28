<?php
require_once __DIR__ . '/../../database/db.php';

function registerUser($name, $email, $age, $gender, $password) {
    global $pdo;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, age, gender, password) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $age, $gender, $hashed]);
}

function userExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
