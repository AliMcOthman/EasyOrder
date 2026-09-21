<?php

// Verbindung zur Datenbank herstellen
require_once "config.php";

// Produkte aus der Datenbank abfragen
$sql = "SELECT id, name, preis, beschreibung, bild FROM produkte";

$result = mysqli_query($conn, $sql);

// Prüfen, ob die Abfrage erfolgreich war
if (!$result) {
    die("Fehler bei der Abfrage: " . mysqli_error($conn));
}

// Produkte in ein Array speichern
$produkte = [];

while ($row = mysqli_fetch_assoc($result)) {
    $produkte[] = $row;
}

// Daten als JSON zurückgeben
header("Content-Type: application/json; charset=UTF-8");

echo json_encode($produkte);

?>