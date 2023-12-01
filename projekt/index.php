<?php
// define('BASE_PATH', 'localhost/projekt/');
include('helpers/SessionHelper.php');

SessionHelper::redirectToDashboard();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logowanie i Rejestracja</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <h2>Logowanie</h2>
    <form action="login.php" method="post">
        <input type="text" name="login" placeholder="Login" required><br><br>
        <input type="password" name="password" placeholder="Hasło" required><br><br>
        <input type="submit" value="Zaloguj">
    </form>
    <?php
    if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
        echo '<p class="red">Niepoprawny login lub hasło.</p>';
    } elseif (isset($_GET['error']) && $_GET['error'] === 'missing') {
        echo '<p class="red">Podano niekompletne dane logowania.</p>';
    }
    ?>

    <h2>Rejestracja</h2>
    <form action="register.php" method="post">
        <input type="text" name="login" placeholder="Login" required><br><br>
        <input type="password" name="password" placeholder="Hasło" required><br><br>
        <input type="text" name="firstName" placeholder="Imię" required><br><br>
        <input type="text" name="lastName" placeholder="Nazwisko" required><br><br>
        <input type="submit" value="Zarejestruj">
    </form>
    <?php

    if (isset($_GET['message']) && $_GET['message'] === 'registered') {
        echo '<p class="green">Użytkownik został pomyślnie zarejestrowany!</p>';
    }
    ?>
</body>

</html>