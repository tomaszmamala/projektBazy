<?php
include('Hotel.php');
include('Entity/Room.php');
use Entity\Hotel;
use Entity\Room;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel = new Hotel();
    $room = new Room();

    $userId = $_SESSION['user_id'] ?? null;
    $roomId = $_POST['roomId'] ?? null;
    $hotelId = $_POST['hotelId'] ?? null;
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;

    if ($userId && $roomId && $startDate && $endDate) {
        if (strtotime($startDate) > strtotime($endDate)) {
            $newUrl = 'hotel.php?' . http_build_query(['id' => $hotelId, 'message' => 'dates']);
            header("Location: $newUrl");

            // header("Location: hotel.php?id=$hotelId?&message=dates");

            echo "Zakres dat został wybrany niepoprawnie!";

            exit();
        }
        if (strtotime($startDate) < time() || strtotime($endDate) < time()) {
            $newUrl = 'hotel.php?' . http_build_query(['id' => $hotelId, 'message' => 'past']);
            header("Location: $newUrl");

            // header("Location: hotel.php?id=$hotelId?&message=past");
            echo "Nie można wybrać daty z przeszłości.";
        } else {
            if ($room->isRoomAvailable($roomId, $startDate, $endDate)) {
                $hotel->makeRoomReservation($userId, $roomId, $startDate, $endDate);

                $newUrl = 'hotel.php?' . http_build_query(['id' => $hotelId, 'message' => 'success']);
                header("Location: $newUrl");

                // header("Location: hotel.php?id=$hotelId?&message=success");
                echo "Rezerwacja została dokonana pomyślnie!";
            } else {
                $newUrl = 'hotel.php?' . http_build_query(['id' => $hotelId, 'message' => 'booked']);
                header("Location: $newUrl");

                // header("Location: hotel.php?id=$hotelId?&message=booked");
                echo "Termin rezerwacji jest zajęty. Proszę wybrać inny termin.";
            }
        }
    } else {
        echo "Błąd: Brak wymaganych danych do dokonania rezerwacji.";
    }
} else {
    echo "Nieprawidłowe zapytanie HTTP.";
}
?>