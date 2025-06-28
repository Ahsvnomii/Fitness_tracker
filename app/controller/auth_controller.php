<?php
session_start();
require_once __DIR__.'/../model/auth_model.php';

$errors = [];
$showLogin = true;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['signup'])) {
        
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $age = (int)$_POST['age'];
        $gender = $_POST['gender'] ?? '';
        $pass = $_POST['password'];

        
        if (!$name) $errors[] = "Name is required";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if ($age < 18) $errors[] = "You must be at least 18";
        if (!in_array($gender, ['male','female'])) $errors[] = "Select gender";
        if (strlen($pass) < 6) $errors[] = "Password must have ≥6 chars";

        if (empty($errors)) {
            if (registerUser($name,$email,$age,$gender,$pass)) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['name'] = $name;
                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Email already registered";
            }
        }
        $showLogin = false;
    }

    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $pass = $_POST['password'];
        if ($u = loginUser($email,$pass)) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['name'] = $u['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Invalid login credentials";
        }
    }
}
