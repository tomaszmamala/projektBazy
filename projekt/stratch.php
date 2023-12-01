<!-- //śmieć-->

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('Hotel.php');
    $hotel = new Hotel();

    $userId = $_SESSION['user_id'] ?? null;
    $hotelId = $_POST['hotelId'] ?? null;
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $numPeople = $_POST['numPeople'] ?? null;

    if ($userId && $hotelId && $startDate && $endDate && $numPeople) {
        if ($hotel->isReservationAvailable($hotelId, $startDate, $endDate)) {
            $hotel->makeReservation($userId, $hotelId, $startDate, $endDate, $numPeople);

            header("Location: hotel.php?id=$hotelId?&message=success");
        } else {
            header("Location: hotel.php?id=$hotelId?&message=booked");
        }
    } else {
        echo "Błąd: Brak wymaganych danych do dokonania rezerwacji.";
    }
} else {
    echo "Nieprawidłowe zapytanie HTTP.";
}
?>