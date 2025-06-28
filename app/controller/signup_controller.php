<?php
session_start();
require_once __DIR__ . '/../model/signup_model.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $age = (int)$_POST['age'];
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'];

    // VALIDATION
    if (!$name || !$email || !$age || !$gender || !$password) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($age < 18) {
        $errors[] = "You must be at least 18 years old.";
    }

    if (!in_array($gender, ['male', 'female'])) {
        $errors[] = "Please select a valid gender.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (userExists($email)) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        if (registerUser($name, $email, $age, $gender, $password)) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['name'] = $name;
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Something went wrong while registering.";
        }
    }
}
