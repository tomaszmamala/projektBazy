<?php
require_once 'Entity\Hotel.php';
use Entity\Hotel;
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hotelId = $_POST['hotelId'];
    $name = $_POST['name'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $stars = $_POST['stars'];
    $imageUrl = $_POST['imageUrl'];

    $hotel = new Hotel();

    $hotel->editHotel($hotelId, $name, $country, $city, $stars);
    $hotel->addPhotoForHotel($hotelId, $imageUrl);

    $newUrl = 'edit_hotel.php?' . http_build_query(['message' => 'edited', 'id' => $hotelId]);
    header("Location: $newUrl");
    echo "Dane hotelu zostały zaktualizowane.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>