<?php

namespace Entity;

require_once 'services\DatabaseHandler.php';
use Services\DatabaseHandler;

class Hotel
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseHandler();
    }

    public function getAllHotels()
    {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM Hotel";
        $stmt = $conn->query($query);

        return $stmt->fetchAll();
    }

    public function getHotelById($hotelId)
    {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM Hotel WHERE id = :hotelId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getOpinionsByHotelId($hotelId)
    {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM Opinie WHERE hotelId = :hotelId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function addOpinion($userId, $hotelId, $opinionText, $date)
    {
        $conn = $this->db->getConnection();

        $query = "INSERT INTO Opinie (uzytkownikId, hotelId, tekst, data) VALUES (:userId, :hotelId, :opinionText, :date)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->bindParam(':opinionText', $opinionText);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
    }

    public function isReservationAvailable($hotelId, $startDate, $endDate)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM Rezerwacje WHERE hotel_id = :hotelId 
                  AND ((data_poczatek <= :startDate AND data_koniec >= :startDate)
                  OR (data_poczatek <= :endDate AND data_koniec >= :endDate)
                  OR (:startDate <= data_poczatek AND :endDate >= data_koniec))";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();

        $existingReservations = $stmt->fetchAll();

        return count($existingReservations) === 0; // Jeśli brak rezerwacji, termin jest dostępny
    }

    public function makeReservation($userId, $hotelId, $startDate, $endDate, $numPeople)
    {
        $conn = $this->db->getConnection();

        $query = "INSERT INTO Rezerwacje (uzytkownikId, hotel_id, data_poczatek, data_koniec) 
                  VALUES (:userId, :hotelId, :startDate, :endDate)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        // $stmt->bindParam(':numPeople', $numPeople);
        $stmt->execute();
    }

    public function makeRoomReservation($userId, $roomId, $startDate, $endDate)
    {
        $conn = $this->db->getConnection();

        $query = "INSERT INTO Rezerwacje (uzytkownikId, pokoj_id, data_poczatek, data_koniec)
                  VALUES (:userId, :roomId, :startDate, :endDate)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':roomId', $roomId);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
    }

    public function getRoomsForHotel($hotelId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM Pokoj 
                  WHERE hotel_id = :hotelId";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editHotel($hotelId, $name, $country, $city, $stars)
    {
        $conn = $this->db->getConnection();
        $query = "UPDATE Hotel SET nazwa = :name, kraj = :country, miasto = :city, gwiazdki = :stars WHERE id = :hotelId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':stars', $stars);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();
    }

    public function deleteHotel($hotelId)
    {
        $conn = $this->db->getConnection();

        $deleteHotelQuery = "DELETE FROM Hotel WHERE id = :hotelId";
        $stmtHotel = $conn->prepare($deleteHotelQuery);
        $stmtHotel->bindParam(':hotelId', $hotelId);
        $stmtHotel->execute();
    }

    public function addHotel($name, $country, $city, $stars, $imageUrl)
    {
        $conn = $this->db->getConnection();
        $query = "INSERT INTO Hotel (nazwa, kraj, miasto, gwiazdki) VALUES (:name, :country, :city, :stars)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':stars', $stars);
        $stmt->execute();

        $hotelId = $conn->lastInsertId();


        $this->addPhotoForHotel($hotelId, $imageUrl);
    }

    public function addRoom($hotelId, $roomName, $roomNumber, $roomType, $numPeople, $price)
    {
        $conn = $this->db->getConnection();
        $query = "INSERT INTO Pokoj (nazwa, numer_pokoju, typ_pokoju, liczba_osob, cena, hotel_id) 
              VALUES (:roomName, :roomNumber, :roomType, :numPeople, :price, :hotelId)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':roomName', $roomName);
        $stmt->bindParam(':roomNumber', $roomNumber);
        $stmt->bindParam(':roomType', $roomType);
        $stmt->bindParam(':numPeople', $numPeople);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();
    }

    public function addPhotoForHotel($hotelId, $imageUrl)
    {
        $conn = $this->db->getConnection();

        $queryImage = "INSERT INTO Zdjecie (url, hotel_id) VALUES (:imageUrl, :hotelId)";
        $stmtImage = $conn->prepare($queryImage);
        $stmtImage->bindParam(':imageUrl', $imageUrl);
        $stmtImage->bindParam(':hotelId', $hotelId);
        $stmtImage->execute();
    }

    public function getFirstPhotoForHotel($hotelId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM Zdjecie WHERE hotel_id = :hotelId LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getAllPhotosForHotel($hotelId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT * FROM Zdjecie WHERE hotel_id = :hotelId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':hotelId', $hotelId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}