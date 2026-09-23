
<?php

// Datenbankverbindung einbinden
require_once "config.php";

// PHPMailer einbinden
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

// Daten aus dem Formular holen
$produktId = $_POST["produktId"];
$productName = $_POST["productName"];
$price = $_POST["price"];
$quantity = $_POST["quantity"];
$total = $_POST["total"];
$fullName = $_POST["fullName"];
$contact = $_POST["contact"];
$email = $_POST["email"];
$address = $_POST["address"];

// SQL-Abfrage vorbereiten
$sql = "INSERT INTO bestellungen
        (produkt_id, produkt_name, preis, menge, gesamtpreis,
         full_name, contact, email, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Werte an die Platzhalter binden
$stmt->bind_param(
    "isdidssss",
    $produktId,
    $productName,
    $price,
    $quantity,
    $total,
    $fullName,
    $contact,
    $email,
    $address
);

// Bestellung speichern
if ($stmt->execute()) {

    // =========================
    // E-MAIL BESTÄTIGUNG
    // =========================

    try {

        $mail = new PHPMailer(true);

        // Gmail SMTP
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        // Deine Gmail-Adresse
        $mail->Username = "alimcothman23@gmail.com";

        // Deine Google App Password
        $mail->Password = "vprqswjhvcbrcroc";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Absender
        $mail->setFrom(
            "alimcothman23@gmail.com",
            "EasyOrder"
        );

        // Empfänger
        $mail->addAddress(
            $email,
            $fullName
        );

        // HTML-E-Mail
        $mail->isHTML(true);

        $mail->Subject = "EasyOrder – Bestellbestätigung";

        // E-Mail-Inhalt
        $mail->Body = "

            <h2 style='color:#15d4ed;'>
                EasyOrder – Bestellbestätigung
            </h2>

            <p>
                Hallo " . htmlspecialchars($fullName) . ",
            </p>

            <p>
                vielen Dank für Ihre Bestellung bei EasyOrder.
            </p>

            <hr>

            <h3>Bestelldetails</h3>

            <p>
                <strong>Produkt:</strong>
                " . htmlspecialchars($productName) . "
            </p>

            <p>
                <strong>Menge:</strong>
                " . htmlspecialchars($quantity) . "
            </p>

            <p>
                <strong>Preis:</strong>
                " . number_format((float)$price, 2, ",", ".") . " €
            </p>

            <p>
                <strong>Gesamtpreis:</strong>
                " . number_format((float)$total, 2, ",", ".") . " €
            </p>

            <p>
                <strong>Adresse:</strong><br>
                " . nl2br(htmlspecialchars($address)) . "
            </p>

            <hr>

            <p>
                Ihre Bestellung wurde erfolgreich aufgenommen.
            </p>

            <p>
                Vielen Dank,<br>
                <strong>EasyOrder</strong>
            </p>

        ";

        // E-Mail senden
        $mail->send();

        echo "Bestellung erfolgreich gespeichert. Eine Bestätigung wurde an Ihre E-Mail-Adresse gesendet.";

    } catch (Exception $e) {

        // Bestellung wurde gespeichert,
        // aber E-Mail konnte nicht gesendet werden.
        echo "Bestellung erfolgreich gespeichert. Die Bestätigungs-E-Mail konnte jedoch nicht gesendet werden.";

    }

} else {

    echo "Fehler beim Speichern der Bestellung.";
}

// Verbindung schließen
$stmt->close();
$conn->close();

?>

