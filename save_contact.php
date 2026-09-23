<?php

// Verbindung zur Datenbank
require_once "config.php";

// Nur POST-Anfragen erlauben
if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Ungültige Anfrage."
    ]);

    exit;
}


// Daten aus dem Formular holen
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");


// Eingaben prüfen
if ($name === "" || $email === "" || $subject === "" || $message === "") {

    echo json_encode([
        "success" => false,
        "message" => "Bitte füllen Sie alle Felder aus."
    ]);

    exit;
}


// E-Mail-Adresse prüfen
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Bitte geben Sie eine gültige E-Mail-Adresse ein."
    ]);

    exit;
}


// SQL vorbereiten
$sql = "INSERT INTO contact_messages (name, email, subject, message)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);


// Prüfen, ob SQL vorbereitet werden konnte
if (!$stmt) {

    echo json_encode([
        "success" => false,
        "message" => "Fehler beim Speichern der Nachricht."
    ]);

    exit;
}


// Werte binden
$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $subject,
    $message
);


// Nachricht speichern
if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Ihre Nachricht wurde erfolgreich gesendet."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Die Nachricht konnte nicht gespeichert werden."
    ]);
}


// Verbindung schließen
$stmt->close();
$conn->close();

?>