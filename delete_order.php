<?php

require_once "config.php";

$orderId = $_POST["orderId"];

$sql = "DELETE FROM bestellungen
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $orderId
);

if ($stmt->execute()) {
    echo "Bestellung erfolgreich gelöscht.";
} else {
    echo "Fehler beim Löschen der Bestellung.";
}

$stmt->close();
$conn->close();

?>