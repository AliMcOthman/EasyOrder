<?php

// Datenbankverbindung einbinden
require_once "config.php";

// Alle Bestellungen aus der Datenbank holen
$sql = "SELECT
            id,
            produkt_id,
            produkt_name,
            preis,
            menge,
            gesamtpreis,
            full_name,
            contact,
            email,
            address,
            bestelldatum,
            status
        FROM bestellungen
        ORDER BY id DESC";

$result = $conn->query($sql);

// Array für die Bestellungen
$bestellungen = [];


// Daten aus der Datenbank durchlaufen
while ($row = $result->fetch_assoc()) {

    $bestellungen[] = $row;
}


// Daten als JSON zurückgeben
header("Content-Type: application/json");

echo json_encode($bestellungen);


// Verbindung schließen
$conn->close();

?>