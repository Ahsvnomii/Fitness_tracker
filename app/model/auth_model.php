<?php
require_once __DIR__ . '/../../database/db.php';

function registerUser($name, $email, $age, $gender, $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name,email,age,gender,password) VALUES (?,?,?,?,?)");
    return $stmt->execute([$name,$email,$age,$gender,$hash]);
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($u && password_verify($password, $u['password'])) return $u;
    return false;
}
