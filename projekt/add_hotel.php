<?php
require_once 'Entity\Hotel.php';
use Entity\Hotel;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $stars = $_POST['stars'];
    $imageUrl = $_POST['imageUrl'];
    $description = $_POST['description'];

    $hotel = new Hotel();

    $hotel->addHotel($name, $country, $city, $stars, $imageUrl, $description);

    $newUrl = 'all_hotels_admin.php?' . http_build_query(['message' => 'added']);
    header("Location: $newUrl");

    echo "Nowy hotel został dodany.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>