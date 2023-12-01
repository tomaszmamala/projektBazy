<?php
namespace Entity;

require_once 'services/DatabaseHandler.php';
use Services\DatabaseHandler;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new DatabaseHandler();
    }

    public function register($login, $password, $firstName, $lastName)
    {
        $conn = $this->db->getConnection();

        // Sprawdzanie, czy użytkownik o podanym loginie już istnieje
        $query = "SELECT id FROM Uzytkownik WHERE login = :login";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false; // Użytkownik o podanym loginie już istnieje
        }

        // Dodawanie nowego użytkownika do bazy danych
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Haszowanie hasła
        $query = "INSERT INTO Uzytkownik (login, haslo, imie, nazwisko, rola) VALUES (:login, :password, :firstName, :lastName, :role)";

        $role = 'USER';
        // $role = 'ADMIN';

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        return true; // Rejestracja zakończona sukcesem
    }

    public function getUserLoginFromId($userId)
    {
        $conn = $this->db->getConnection();

        $query = "SELECT login FROM Uzytkownik WHERE id = :userId";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            return $result['login'];
        } else {
            return null; // Lub możesz obsłużyć brak danych w inny sposób
        }
    }

    public function login($login, $password)
    {
        $conn = $this->db->getConnection();
        // Pobieranie hasła z bazy danych na podstawie loginu
        $query = "SELECT id, haslo, rola FROM Uzytkownik WHERE login = :login";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['haslo'])) {
            // Logowanie zakończone sukcesem
            session_start();
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['rola'];

            return true;
        } else {
            return false; // Niepoprawny login lub hasło
        }
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Location: index.php");

        exit();
    }
}

