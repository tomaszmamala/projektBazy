<?php
include('helpers/SessionHelper.php');
require_once 'Entity/Hotel.php';
require_once 'Entity/Room.php';  // Dodane dołączenie klasy Room
use Entity\Hotel;
use Entity\Room;  // Dodane dołączenie klasy Room

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/menu_bar.css">
</head>

<body>
    <div class="menu">
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <?php
            if ($_SESSION['user_role'] === 'ADMIN') {
                echo '<a href="all_hotels_admin.php">Zarządzaj hotelami</a>';
            }
        ?>
        <a href="dashboard.php">Strona główna</a>
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
    </div>

    <div class="container">
        <h2>Lista Hoteli</h2>
        <ul class="hotels-list">
            <?php
            $hotel = new Hotel();
            $room = new Room();  // Dodane utworzenie obiektu klasy Room

            $hotels = $hotel->getAllHotels();

            foreach ($hotels as $h) {
                $img = $hotel->getFirstPhotoForHotel($h['id']);
                $img["url"] = $img["url"] ?? 'https://media.istockphoto.com/id/1354776457/vector/default-image-icon-vector-missing-picture-page-for-website-design-or-mobile-app-no-photo.jpg?s=612x612&w=0&k=20&c=w3OW0wX3LyiFRuDHo9A32Q0IUMtD4yjXEvQlqyYk9O4=';
                
                echo '<li>';
                echo '<a href="hotel.php?id=' . $h['id'] . '">' . $h['nazwa'] . '</a>';
                echo '<img src="'. $img["url"] .'"/>';
                echo '<p>' . $h['opis'] . '</p>';
                
                // Dodajemy wyświetlanie widełek cenowych
                $minPrice = $room->getMinRoomPrice($h['id']);
                $maxPrice = $room->getMaxRoomPrice($h['id']);
                
                if ($minPrice !== null && $maxPrice !== null) {
                    echo '<p>Widełki cenowe: ' . $minPrice . ' - ' . $maxPrice . ' PLN</p>';
                } else {
                    echo '<p>Brak informacji o cenach.</p>';
                }
                
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</body>

</html>