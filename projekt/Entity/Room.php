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
}
?>