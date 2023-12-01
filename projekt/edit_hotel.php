<?php
include('helpers/SessionHelper.php');
require_once 'Entity\Hotel.php';
use Entity\Hotel;

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Logowanie i Rejestracja</title>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <div class="menu">
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <a href="all_hotels_admin.php">Zarządzaj hotelami</a>
        <a href="dashboard.php">Strona główna</a>
    </div>

    <?php
    $userRole = $_SESSION['user_role'];
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && $userRole === 'ADMIN') {
        $hotelId = $_GET['id'];

        $hotel = new Hotel();

        $selectedHotel = $hotel->getHotelById($hotelId);

        if ($selectedHotel) {
            // Formularz do edycji hotelu
            echo "<h2>Edytuj hotel:</h2>";
            echo "<form action='update_hotel.php' method='POST'>";
            echo "<input type='hidden' name='hotelId' value='" . $selectedHotel['id'] . "'>";
            echo "Nazwa: <input type='text' name='name' value='" . $selectedHotel['nazwa'] . "'><br>";
            echo "Kraj: <input type='text' name='country' value='" . $selectedHotel['kraj'] . "'><br>";
            echo "Miasto: <input type='text' name='city' value='" . $selectedHotel['miasto'] . "'><br>";
            echo "Gwiazdki: <input type='number' name='stars' value='" . $selectedHotel['gwiazdki'] . "'><br>";
            echo "Dodaj zdjęcie: <input type='text' name='imageUrl' value=''><br>";
            echo "<input type='submit' value='Zapisz zmiany'>";
            echo "</form>";

            if (isset($_GET['message']) && $_GET['message'] === 'edited') {
                echo 'Dane hotelu zostały pomyślnie zmienione.';
            }
        } else {
            echo "Hotel o podanym ID nie istnieje.";
        }
    } else {
        echo "Nieprawidłowe żądanie bądź nie posiadzasz uprawnień.";
    }
    ?>

</body>

</html>