<?php
$pdo = new PDO("mysql:host=localhost;dbname=fitness_tracker", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
