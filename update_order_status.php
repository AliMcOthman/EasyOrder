<?php

require_once "config.php";

$orderId = $_POST["orderId"];
$status = $_POST["status"];

$sql = "UPDATE bestellungen
        SET status = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $status,
    $orderId
);

if ($stmt->execute()) {
    echo "Status erfolgreich aktualisiert.";
} else {
    echo "Fehler beim Aktualisieren des Status.";
}

$stmt->close();
$conn->close();

?>