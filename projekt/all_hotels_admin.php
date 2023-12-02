<?php
include('helpers/SessionHelper.php');
require_once 'Entity\Hotel.php';
use Entity\Hotel;

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles/all_hotels_admin.css">
    <link rel="stylesheet" href="styles/menu_bar.css">
</head>

<body>
    <div class="menu">       
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <?php 
            if ($_SESSION['user_role'] === 'ADMIN'){
                echo '<a href="all_hotels_admin.php">Zarządzaj hotelami</a>';
            }
        ?>
        <a href="dashboard.php">Strona główna</a>
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
    </div>


    <?php
    $hotel = new Hotel();

    $userRole = $_SESSION['user_role'];

    if ($userRole === 'ADMIN') {
        $hotels = $hotel->getAllHotels();

        if ($hotels) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Nazwa</th><th>Kraj</th><th>Miasto</th><th>Gwiazdki</th><th>Akcje</th></tr>";
            foreach ($hotels as $hotelData) {
                echo "<tr>";
                echo "<td>" . $hotelData['id'] . "</td>";
                echo "<td>" . $hotelData['nazwa'] . "</td>";
                echo "<td>" . $hotelData['kraj'] . "</td>";
                echo "<td>" . $hotelData['miasto'] . "</td>";
                echo "<td>" . $hotelData['gwiazdki'] . "</td>";
                echo "<td>
                            <a href='edit_hotel.php?id=" . $hotelData['id'] . "'>Edytuj</a> | 
                            <a href='delete_hotel.php?id=" . $hotelData['id'] . "'>Usuń</a> |
                            <a href='add_room.php?id=" . $hotelData['id'] . "'>Dodaj pokój</a></td>";
                            
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Brak hoteli";
        }
        if (isset($_GET['message']) && $_GET['message'] === 'added') {
            echo 'Nowy hotel został dodany.';
        } elseif (isset($_GET['message']) && $_GET['message'] === 'deleted') {
            echo 'Hotel został usunięty.';
        }

        // Dodawanie nowego hotelu
        echo "<div class='add-hotel-container'>";
            echo "<h2>Dodaj nowy hotel:</h2>";
            echo "<form action='add_hotel.php' method='POST'>";
            echo "<label for='name'>Nazwa:</label><br>";
            echo "<input type='text' name='name' id='name'><br>";
            echo "<label for='country'>Kraj:</label><br>";
            echo "<input type='text' name='country' id='country'><br>";
            echo "<label for='city'>Miasto:</label><br>";
            echo "<input type='text' name='city' id='city'><br>";
            echo "<label for='stars'>Gwiazdki:</label><br>";
            echo "<input type='number' name='stars' id='stars'><br>";
            echo "<label for='imageUrl'>Zdjęcie (Url):</label><br>";
            echo "<input type='text' name='imageUrl' id='imageUrl'><br>";
            echo "<input class='send-form' type='submit' value='Dodaj hotel'>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "Nie masz uprawnień do przeglądania hoteli";
        }
    ?>
</body>

</html>