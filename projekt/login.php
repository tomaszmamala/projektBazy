<?php
// include '../Entity/User.php';
require_once 'Entity/User.php';
use Entity\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (empty($login) || empty($password)) {
        $newUrl = 'index.php?' . http_build_query(['error' => 'missing']);
        header("Location: $newUrl");

        // header("Location: index.php?error=missing");

        exit();
    }

    if ($user->login($login, $password)) {
        header("Location: dashboard.php");

        exit();
    } else {
        $newUrl = 'index.php?' . http_build_query(['error' => 'invalid']);
        
        header("Location: $newUrl");
        exit();
    }
}