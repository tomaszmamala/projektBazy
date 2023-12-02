<?php

namespace Entity;

require_once 'services\DatabaseHandler.php';
use Services\DatabaseHandler;

class Room
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseHandler();
    }

    public function getRoomNameFromId($roomId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT nazwa FROM pokoj WHERE id = :room_id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':room_id', $roomId);

        $statement->execute();

        $room = $statement->fetch(\PDO::FETCH_ASSOC);

        return ($room) ? $room['nazwa'] : null;
    }

    public function isRoomAvailable($roomId, $startDate, $endDate)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM Rezerwacje WHERE pokoj_id = :roomId
                  AND ((data_poczatek <= :startDate AND data_koniec >= :startDate)
                  OR (data_poczatek <= :endDate AND data_koniec >= :endDate)
                  OR (:startDate <= data_poczatek AND :endDate >= data_koniec))";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':roomId', $roomId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();

        $existingReservations = $stmt->fetchAll();

        return count($existingReservations) === 0; // Jeśli brak rezerwacji, pokój jest dostępny
    }

    public function getMaxRoomPrice($hotelId)
{
    $conn = $this->db->getConnection();

    $query = "SELECT MAX(cena) as max_price FROM Pokoj WHERE hotel_id = :hotelId";
    $statement = $conn->prepare($query);
    $statement->bindParam(':hotelId', $hotelId);
    $statement->execute();

    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    return ($result) ? $result['max_price'] : null;
}

public function getMinRoomPrice($hotelId)
{
    $conn = $this->db->getConnection();

    $query = "SELECT MIN(cena) as min_price FROM Pokoj WHERE hotel_id = :hotelId";
    $statement = $conn->prepare($query);
    $statement->bindParam(':hotelId', $hotelId);
    $statement->execute();

    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    return ($result) ? $result['min_price'] : null;
}
}
?>