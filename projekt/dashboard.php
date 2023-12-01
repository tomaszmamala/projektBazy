<?php
include('helpers/SessionHelper.php');
require_once 'Entity/Hotel.php';
use Entity\Hotel;

SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <style>
        .hotels-list {
            list-style: none;
            padding: 0;
        }

        .hotels-list li {
            margin-bottom: 10px;
        }

        .hotels-list li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
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


    <h2>Lista Hoteli</h2>
    <ul class="hotels-list">
        <?php
        $hotel = new Hotel();

        $hotels = $hotel->getAllHotels();

        foreach ($hotels as $h) {
            $img = $hotel->getFirstPhotoForHotel($h['id']);
            $img["url"] = $img["url"] ?? 'https://media.istockphoto.com/id/1354776457/vector/default-image-icon-vector-missing-picture-page-for-website-design-or-mobile-app-no-photo.jpg?s=612x612&w=0&k=20&c=w3OW0wX3LyiFRuDHo9A32Q0IUMtD4yjXEvQlqyYk9O4=';
            
            echo '<li><a href="hotel.php?id=' . $h['id'] . '">' . $h['nazwa'] . '</a>';
            echo '<img src="'. $img["url"] .'"/></li>';
        }
        ?>
    </ul>
</body>

</html>