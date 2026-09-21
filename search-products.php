<?php

// Verbindung zur Datenbank herstellen
require_once "config.php";


// Suchbegriff aus der URL holen
$search = $_GET["search"] ?? "";


// Suchbegriff für SQL vorbereiten
$search = mysqli_real_escape_string($conn, $search);


// Produkte suchen
$sql = "SELECT id, name, preis, beschreibung, bild
        FROM produkte
        WHERE name LIKE '%$search%'";

$result = mysqli_query($conn, $sql);


// Prüfen, ob die Abfrage erfolgreich war
if (!$result) {
    die("Fehler bei der Suche: " . mysqli_error($conn));
}


// Ergebnisse in ein Array speichern
$produkte = [];

while ($row = mysqli_fetch_assoc($result)) {
    $produkte[] = $row;
}


// Daten als JSON zurückgeben
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($produkte);

?>

