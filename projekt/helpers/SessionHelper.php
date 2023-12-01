<?php
include('Entity/User.php');

class SessionHelper
{
    public static function loggedIn()
    {
        session_start();

        if (!isset($_SESSION['logged_in'])) {
            header("Location: index.php");

            exit();
        }
    }

    public static function redirectToDashboard()
    {
        session_start();

        if (isset($_SESSION['logged_in'])) {
            header("Location: dashboard.php");

            exit();
        }
    }
}
?>
