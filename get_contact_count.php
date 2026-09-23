
<?php

// Verbindung zur Datenbank
require_once "config.php";

// Anzahl der Contact-Nachrichten abrufen
$sql = "SELECT COUNT(*) AS anzahl FROM contact_messages";

$result = $conn->query($sql);

if ($result) {

    $row = $result->fetch_assoc();

    echo json_encode([
        "count" => (int)$row["anzahl"]
    ]);

} else {

    echo json_encode([
        "count" => 0
    ]);
}

$conn->close();

?>

