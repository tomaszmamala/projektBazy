<?php
require_once 'Entity\Hotel.php';
use Entity\Hotel;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobranie danych z formularza
    $hotelId = $_POST['hotelId'];
    $roomName = $_POST['roomName'];
    $roomNumber = $_POST['roomNumber'];
    $roomType = $_POST['roomType'];
    $numPeople = $_POST['numPeople'];
    $price = $_POST['price'];

    // Inicjalizacja obiektu klasy Hotel
    $hotel = new Hotel();

    $hotel->addRoom($hotelId, $roomName, $roomNumber, $roomType, $numPeople, $price);

    $newUrl = 'add_room.php?' . http_build_query(['id' => $hotelId]);
    header("Location: $newUrl");
    // echo "Nowy pokój został dodany dla hotelu o ID: " . $hotelId;
} else {
    echo "Nieprawidłowe żądanie.";
}
?>