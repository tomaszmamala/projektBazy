<?php
include 'Entity/User.php';
use Entity\User;

// Obsługa rejestracji
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $login = $_POST['login'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    $user->register($login, $password, $firstName, $lastName);

    header("Location: index.php?message=registered");
}
?>
