<?php
include('helpers/SessionHelper.php');
require_once 'Entity\Hotel.php';
use Entity\Hotel;

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dodawanie pokoju</title>
    <link rel="stylesheet" href="styles/add_room.css">
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

        $rooms = $hotel->getRoomsForHotel($hotelId);

        if ($rooms) {
            echo "<h2>Pokoje dla hotelu o ID: " . $hotelId . "</h2>";
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Nazwa</th><th>Numer pokoju</th><th>Typ pokoju</th><th>Liczba osób</th><th>Cena</th></tr>";
            foreach ($rooms as $room) {
                echo "<tr>";
                echo "<td>" . $room['id'] . "</td>";
                echo "<td>" . $room['nazwa'] . "</td>";
                echo "<td>" . $room['numer_pokoju'] . "</td>";
                echo "<td>" . $room['typ_pokoju'] . "</td>";
                echo "<td>" . $room['liczba_osob'] . "</td>";
                echo "<td>" . $room['cena'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Brak pokoi dla tego hotelu.";
        }

        echo "<h2>Dodaj nowy pokój dla hotelu " . $hotelId . ":</h2>";
        echo "<form action='save_room.php' method='POST'>";
        echo "<input type='hidden' name='hotelId' value='" . $hotelId . "'>";
        echo "Nazwa: <input type='text' name='roomName'><br>";
        echo "Numer pokoju: <input type='text' name='roomNumber'><br>";
        echo "Typ pokoju: <input type='text' name='roomType'><br>";
        echo "Liczba osób: <input type='number' name='numPeople'><br>";
        echo "Cena: <input type='number' name='price'><br>";
        echo "<input type='submit' value='Dodaj pokój'>";
        echo "</form>";

    } else {
        echo "Nieprawidłowe żądanie bądź nie posiadzasz uprawnień.";
    }
    ?>
</body>

</html>