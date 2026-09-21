<?php

// Verbindung mit der Datenbank herstellen
$host = "localhost";
$dbname = "easyfood_db";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password, $dbname);

// Verbindung prüfen
if (!$conn) {
    die("Datenbankverbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Zeichencodierung festlegen
mysqli_set_charset($conn, "utf8mb4");

?>