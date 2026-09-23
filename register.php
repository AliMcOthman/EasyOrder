<?php

session_start();

require_once "config.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

$error = "";
$success = "";


// Registrierung
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];


    // Prüfen
    if ($password !== $confirmPassword) {

        $error = "Die Passwörter stimmen nicht überein.";

    } else {


        // Prüfen, ob Benutzername oder E-Mail bereits existiert
        $sql = "SELECT id
                FROM kunden
                WHERE username = ? OR email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $username,
            $email
        );

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result->num_rows > 0) {

            $error =
                "Benutzername oder E-Mail-Adresse existiert bereits.";

        } else {


            // Passwort verschlüsseln
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Bestätigungstoken erstellen
            $verificationToken = bin2hex(random_bytes(32));


            // Kunden speichern
            $sql = "INSERT INTO kunden
                    (username, email, password, verification_token)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssss",
                $username,
                $email,
                $hashedPassword,
                $verificationToken
            );


            /* if ($stmt->execute()) {

                $success =
                    "Konto wurde erfolgreich erstellt.";

            } else {

                $error =
                    "Fehler beim Erstellen des Kontos.";
            } */
            if ($stmt->execute()) {

                try {

                    $mail = new PHPMailer(true);

                    // Gmail SMTP
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "alimcothman23@gmail.com";

                    // Hier dein App-Passwort eintragen
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
                        $username
                    );

                    // E-Mail Inhalt
                    $mail->isHTML(true);

                    $mail->Subject = "EasyOrder – E-Mail bestätigen";

                    $verificationLink =
                        "http://localhost/EasyOrder/verify-email.php?token="
                        . urlencode($verificationToken);

                    $mail->Body = "
                        <h2>Willkommen bei EasyOrder!</h2>

                        <p>Hallo " . htmlspecialchars($username) . ",</p>

                        <p>
                            Vielen Dank für deine Registrierung.
                        </p>

                        <p>
                            Bitte bestätige deine E-Mail-Adresse:
                        </p>

                        <p>
                            <a href='" . $verificationLink . "'>
                                E-Mail-Adresse bestätigen
                            </a>
                        </p>

                        <p>
                            Vielen Dank,<br>
                            EasyOrder
                        </p>
                    ";

                    // E-Mail senden
                    $mail->send();

                    $success =
                        "Konto wurde erfolgreich erstellt. "
                        . "Bitte überprüfen Sie Ihre E-Mail-Adresse.";

                } catch (Exception $e) {

                    $success =
                        "Konto wurde erstellt, aber die Bestätigungs-E-Mail "
                        . "konnte nicht gesendet werden.";

                    $error = $mail->ErrorInfo;
                }

            } else {

                $error = "Fehler beim Erstellen des Kontos.";
            }


        }

        $stmt->close();
    }
}

?>


<!DOCTYPE html>

<html lang="de">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EasyOrder - Registrierung</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>


<body>


<section class="food-search text-center">

    <div class="container">

        <h1>EasyOrder</h1>

        <p>Neues Konto erstellen</p>

    </div>

</section>


<section>

    <div class="container">

        <h2>Registrierung</h2>


        <?php if ($error !== "") : ?>

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>


        <?php if ($success !== "") : ?>

            <p>
                <?php echo htmlspecialchars($success); ?>
            </p>

        <?php endif; ?>


        <form method="POST"
              action="register.php">


            <!-- Benutzername -->

            <div class="order-label">
                Benutzername
            </div>

            <input
                type="text"
                name="username"
                class="input-responsive"
                required>


            <!-- E-Mail -->

            <div class="order-label">
                E-Mail
            </div>

            <input
                type="email"
                name="email"
                class="input-responsive"
                required>


            <!-- Passwort -->

            <div class="order-label">
                Passwort
            </div>

            <input
                type="password"
                name="password"
                class="input-responsive"
                required>


            <!-- Passwort bestätigen -->

            <div class="order-label">
                Passwort bestätigen
            </div>

            <input
                type="password"
                name="confirm_password"
                class="input-responsive"
                required>


            <br>


            <input
                type="submit"
                value="Registrieren"
                class="btn btn-primary">


        </form>

    </div>

</section>


</body>

</html>
