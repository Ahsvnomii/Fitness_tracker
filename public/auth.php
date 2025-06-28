<?php
$action = $_GET['action'] ?? 'login';

if ($action === 'signup') {
    require_once __DIR__ . '/../app/controller/signup_controller.php';
    require_once __DIR__ . '/../app/view/signup_view.php';
} else {
    require_once __DIR__ . '/../app/controller/auth_controller.php';
    require_once __DIR__ . '/../app/view/auth_view.php';
}
