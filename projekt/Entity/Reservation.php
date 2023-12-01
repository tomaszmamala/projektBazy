<?php

namespace Entity;

require_once 'services\DatabaseHandler.php';
use Services\DatabaseHandler;

class Reservation
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseHandler();
    }

    public function getUserReservations($userId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM rezerwacje WHERE uzytkownikId = :user_id";

        $statement = $conn->prepare($query);
        $statement->bindParam(':user_id', $userId);

        $statement->execute();

        $userReservations = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $userReservations;
    }

    public function getRoomId($reservationId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT pokoj_id FROM rezerwacje WHERE id = :reservation_id";

        $statement = $conn->prepare($query);
        $statement->bindParam(':reservation_id', $reservationId);

        $statement->execute();

        $reservation = $statement->fetch(\PDO::FETCH_ASSOC);

        return ($reservation) ? $reservation['pokoj_id'] : null;
    }
}