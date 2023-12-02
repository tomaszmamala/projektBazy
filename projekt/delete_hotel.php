<?php
require_once 'Entity\Hotel.php';
use Entity\Hotel;

?>

<?php
require_once 'Hotel.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $hotelId = $_GET['id'];

    $hotel = new Hotel();

    $hotel->deleteHotel($hotelId);

    $newUrl = 'all_hotels_admin.php?' . http_build_query(['message' => 'deleted', 'id' => $hotelId]);
    header("Location: $newUrl");
    echo "Hotel został usunięty.";
} else {
    echo "Nieprawidłowe żądanie.";
}
?>