<?php

session_start();

require_once "config.php";


// Prüfen, ob der Kunde angemeldet ist
if (!isset($_SESSION["kunden_id"])) {

    echo json_encode([
        "logged_in" => false
    ]);

    exit;
}


// Kundendaten abrufen
$kundenId = $_SESSION["kunden_id"];

$sql = "SELECT username, email
        FROM kunden
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $kundenId);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $kunde = $result->fetch_assoc();

    echo json_encode([
        "logged_in" => true,
        "username" => $kunde["username"],
        "email" => $kunde["email"]
    ]);

} else {

    echo json_encode([
        "logged_in" => false
    ]);
}


$stmt->close();

?>