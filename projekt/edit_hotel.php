<?php
include('helpers/SessionHelper.php');
require_once 'Entity\Hotel.php';
use Entity\Hotel;

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edytowanie hotelu</title>
    <link rel="stylesheet" href="styles/edit_hotel.css">
    <link rel="stylesheet" href="styles/menu_bar.css">
</head>

<body>
    <div class="menu">        
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <a href="all_hotels_admin.php">Zarządzaj hotelami</a>
        <a href="dashboard.php">Strona główna</a>
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
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
            echo "Nazwa: <input type='text' name='name' value='" . $selectedHotel['nazwa'] . "' required><br>";
            echo "Kraj: <input type='text' name='country' value='" . $selectedHotel['kraj'] . "' required><br>";
            echo "Miasto: <input type='text' name='city' value='" . $selectedHotel['miasto'] . "' required><br>";
            echo "Gwiazdki: <input type='number' name='stars' value='" . $selectedHotel['gwiazdki'] . "' required><br>";
            echo "Opis: <textarea name='description' required>" . $selectedHotel['opis'] . "</textarea><br>";
            echo "Dodaj zdjęcie: <input type='text' name='imageUrl' value=''><br>";
            echo "<input class='send-form' type='submit' value='Zapisz zmiany'>";
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