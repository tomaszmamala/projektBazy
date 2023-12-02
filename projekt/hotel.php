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
    <link rel="stylesheet" href="styles/hotel.css">
    <link rel="stylesheet" href="styles/menu_bar.css">
</head>

<body>
    <div class="menu">
        <a href="user_reservations.php">Twoje rezerwacje</a>
        <?php
            if ($_SESSION['user_role'] === 'ADMIN') {
                echo '<a href="all_hotels_admin.php">Zarządzaj hotelami</a>';
            }
        ?>
        <a href="dashboard.php">Strona główna</a>
        <form action="logout.php" method="post">
            <input type="submit" value="Wyloguj">
        </form>
    </div>

    <?php
    $hotel = new Hotel();
    $user = new User();

    $hotelId = $_GET['id'] ?? null;
    $selectedHotel = $hotel->getHotelById($hotelId);

    if (!$selectedHotel) {
        echo "<p>Nie znaleziono hotelu o podanym ID.</p>";
    } else {
        echo '<div id="hotel-info">';
        echo "<h2>{$selectedHotel['nazwa']}</h2>";
        echo "<p>Kraj: {$selectedHotel['kraj']}</p>";
        echo "<p>Miasto: {$selectedHotel['miasto']}</p>";
        echo "<p>Gwiazdki: {$selectedHotel['gwiazdki']}</p>";
        echo "<p>Opis: {$selectedHotel['opis']}</p>";
        echo '<div id="hotel-images">';
        $images = $hotel->getAllPhotosForHotel($hotelId);

        if ($images) {
            echo '<img src="' . $images[0]['url'] . '" alt="Hotel Image">';

            if (count($images) > 1) {
                echo '<div id="prev-image" onclick="changeImage(-1)">❮</div>';
                echo '<div id="next-image" onclick="changeImage(1)">❯</div>';
            }
        } else {
            echo '<p>Brak zdjęć.</p>';
        }
        echo '</div>';
        echo '</div>';

        echo '<div class="reviews">';
        echo '<h3>Opinie:</h3>';

        $opinions = $hotel->getOpinionsByHotelId($hotelId);

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
            <input class="send-form" type="submit" value="Dodaj opinię">
        </form>
        ';
        ?>

        <form action="make_reservation.php" method="post">
            <?php echo '<input type="hidden" name="hotelId" value="' . $hotelId . '">' ?>
            <label for="room">Wybierz pokój:</label>
            <select id="room" name="roomId" required>
                <?php
                
                $availableRooms = $hotel->getRoomsForHotel($hotelId);
            
                foreach ($availableRooms as $room) {
                    echo "<option value='" . $room['id'] . "'>" . $room['nazwa'] . " - nr" . $room['numer_pokoju'] . " - " . $room['typ_pokoju'] . "</option>";
                }
                ?>
            </select><br>

            <label for="startDate">Data rozpoczęcia:</label>
            <input type="date" id="startDate" name="startDate" min="<?php echo date('Y-m-d'); ?>" required><br>

            <label for="endDate">Data zakończenia:</label>
            <input type="date" id="endDate" name="endDate" min="<?php echo date('Y-m-d'); ?>" required><br>

            <input class="send-form" type="submit" value="Zarezerwuj">
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
<script>
    // Nowy skrypt do obsługi zmiany obrazu
    let currentImage = 0;
    const images = <?php echo json_encode($images); ?>;

    function changeImage(offset) {
        currentImage += offset;

        if (currentImage < 0) {
            currentImage = images.length - 1;
        } else if (currentImage >= images.length) {
            currentImage = 0;
        }

        document.querySelector('#hotel-images img').src = images[currentImage]['url'];
    }
</script>


</html>