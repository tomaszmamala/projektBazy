<?php
include('Entity/Hotel.php');
use Entity\Hotel;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $hotel = new Hotel();

    session_start();

    $userId = $_SESSION['user_id'] ?? null;
    $hotelId = $_POST['hotelId'] ?? null;
    $opinionText = $_POST['opinionText'] ?? null;
    $date = date('Y-m-d');


    if ($userId && $hotelId && $opinionText) {
        $hotel->addOpinion($userId, $hotelId, $opinionText, $date);

        header("Location: hotel.php?id=" . $hotelId);
        exit();
    } else {
        echo "Błąd: Brak wymaganych danych do dodania opinii.";
    }
} else {
    echo "Nieprawidłowe zapytanie HTTP.";
}
?>