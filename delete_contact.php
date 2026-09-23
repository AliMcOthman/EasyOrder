
<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "config.php";


// Nur POST-Anfragen erlauben
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Ungültige Anfrage."
    ]);

    exit;
}


// ID holen
$id = intval($_POST["id"] ?? 0);


// Prüfen
if ($id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Ungültige Nachrichten-ID."
    ]);

    exit;
}


// SQL vorbereiten
$sql = "DELETE FROM contact_messages WHERE id = ?";

$stmt = $conn->prepare($sql);


if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "SQL-Fehler: " . $conn->error
    ]);

    exit;
}


// ID binden
$stmt->bind_param("i", $id);


// Löschen
if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "Nachricht wurde gelöscht."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Nachricht wurde nicht gefunden."
        ]);
    }

} else {

    echo json_encode([
        "success" => false,
        "message" => "Fehler beim Löschen der Nachricht."
    ]);
}


// Verbindung schließen
$stmt->close();
$conn->close();

?>

