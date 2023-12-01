<?php
include('helpers/SessionHelper.php');

SessionHelper::loggedIn();
?>

<?php
$userId = $_SESSION['user_id'] ?? null;

include('Entity/Reservation.php');
include('Entity/Room.php');
use Entity\Reservation;
use Entity\Room;

$reservation = new Reservation();
$room = new Room();

$userReservations = $reservation->getUserReservations($userId);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Twoje rezerwacje</title>
    <link rel="stylesheet" href="styles/user_reservations.css">
</head>

<body> 
    <div class="menu">      
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <a href="dashboard.php">Strona główna</a>
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
    </div>

    <h1>Twoje rezerwacje</h1>
    <?php if (!empty($userReservations)): ?>
        <ul>
            <?php foreach ($userReservations as $res):
                $roomName = $room->getRoomNameFromId($reservation->getRoomId($res['id']));
                ?>
                <li>
                    Pokój:
                    <?php echo $roomName; ?><br>
                    Data rozpoczęcia:
                    <?php echo $res['data_poczatek']; ?><br>
                    Data zakończenia:
                    <?php echo $res['data_koniec']; ?><br>
                    <!-- Tutaj możesz wyświetlić inne informacje o rezerwacji -->
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Brak rezerwacji.</p>
    <?php endif; ?>


</body>

</html>