<?php
include('helpers/SessionHelper.php');
require_once 'Entity\User.php';
require_once 'Entity\Hotel.php';
use Entity\User;
use Entity\Hotel;
SessionHelper::loggedIn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hotel</title>
    <style>
        .reviews {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }

        .reviews h3 {
            margin-bottom: 10px;
        }

        .reviews ul {
            list-style: none;
            padding: 0;
        }

        .reviews li {
            margin-bottom: 15px;
        }
    </style>
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <div class="menu">
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <a href="dashboard.php">Strona główna</a>
    </div>


    <?php
    $hotel = new Hotel();
    $user = new User();

    $hotelId = $_GET['id'] ?? null;
    $selectedHotel = $hotel->getHotelById($hotelId);

    if (!$selectedHotel) {
        echo "<p>Nie znaleziono hotelu o podanym ID.</p>";
    } else {
        echo "<h2>{$selectedHotel['nazwa']}</h2>";
        echo "<p>Kraj: {$selectedHotel['kraj']}</p>";
        echo "<p>Miasto: {$selectedHotel['miasto']}</p>";
        echo "<p>Gwiazdki: {$selectedHotel['gwiazdki']}</p>";
        // echo `<img src="{$selectedHotel['zdjecie']}" />`; tu trzeba wyjac zdjecia dla tego hotelu i je wyswietlic wszystkie
    

        echo '<div class="reviews">';
        echo '<h3>Opinie:</h3>';
        $opinions = $hotel->getOpinionsByHotelId($hotelId); // Pobranie opinii dla danego hotelu
        if ($opinions) {
            echo '<ul>';
            foreach ($opinions as $opinion) {
                $login = $user->getUserLoginFromId($opinion['uzytkownikId']);

                echo "<li>{$opinion['tekst']} - {$opinion['data']}</li>";
                echo "<li>dodane przez: <b>{$login}</b></li>";
            }

            echo '</ul>';
        } else {
            echo "<p>Brak opinii.</p>";
        }

        // Formularz dodawania opinii
        echo '
        <h3>Dodaj opinię:</h3>
        <form action="add_opinion.php" method="post">
            <input type="hidden" name="hotelId" value="' . $hotelId . '">
            <textarea name="opinionText" placeholder="Twoja opinia" required></textarea><br><br>
            <input type="submit" value="Dodaj opinię">
        </form>
        ';

        // Formularz rezerwacji
        // echo '
        // <h3>Rezerwacja:</h3>
        // <form action="make_reservation.php" method="post">
        //     <input type="hidden" name="hotelId" value="' . $hotelId . '">
        //     Data początkowa: <input type="date" name="startDate" required><br><br>
        //     Data końcowa: <input type="date" name="endDate" required><br><br>
        //     Liczba osób: <input type="number" name="numPeople" required><br><br>
        //     <input type="submit" value="Zarezerwuj">
        // </form>
        // ';
        ?>

        <form action="make_reservation.php" method="post">
            <?php echo '<input type="hidden" name="hotelId" value="' . $hotelId . '">' ?>
            <label for="room">Wybierz pokój:</label>
            <select id="room" name="roomId" required>
                <?php
                // Pobierz dostępne pokoje z bazy danych (to jest przykładowy kod, dostosuj go do swojej logiki)
                $availableRooms = $hotel->getRoomsForHotel($hotelId); // Metoda do pobrania dostępnych pokoi z bazy
            
                foreach ($availableRooms as $room) {
                    echo "<option value='" . $room['id'] . "'>" . $room['nazwa'] . " - nr" . $room['numer_pokoju'] . " - " . $room['typ_pokoju'] . "</option>";
                }
                ?>
            </select><br>


            <label for="startDate">Data rozpoczęcia:</label>
            <input type="date" id="startDate" name="startDate" min="<?php echo date('Y-m-d'); ?>" required><br>

            <label for="endDate">Data zakończenia:</label>
            <input type="date" id="endDate" name="endDate" min="<?php echo date('Y-m-d'); ?>" required><br>

            <input type="submit" value="Zarezerwuj">
        </form>

        <?php
        if (isset($_GET['message']) && $_GET['message'] === 'success') {
            echo '<p class="green">Rezerwacja została dokonana pomyślnie!</p>';
        } elseif (isset($_GET['message']) && $_GET['message'] === 'booked') {
            echo '<p class="red">Termin rezerwacji jest zajęty. Proszę wybrać inny termin.</p>';
        } elseif (isset($_GET['message']) && $_GET['message'] === 'past') {
            echo '<p class="red">Nie można wybrać daty z przeszłości.</p>';
        } elseif (isset($_GET['message']) && $_GET['message'] === 'dates') {
            echo '<p class="red">Zakres dat został wybrany niepoprawnie!</p>';
        }

        echo '</div>';
    }
    ?>
</body>

</html>