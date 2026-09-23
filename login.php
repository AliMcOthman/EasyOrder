<?php

session_start();

require_once "config.php";

$error = "";


// Login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];


    // Kunden suchen
    $sql = "SELECT id, username, email, password, email_verified
            FROM kunden
            WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();


    // Kunde gefunden
    if ($result->num_rows === 1) {

        $kunde = $result->fetch_assoc();


        // Passwort prüfen
        if (password_verify($password, $kunde["password"])) {

            if ($kunde["email_verified"] == 0) {

                $error = "Bitte bestätigen Sie zuerst Ihre E-Mail-Adresse.";

            } else {

                $_SESSION["kunden_id"] = $kunde["id"];
                $_SESSION["kunden_username"] = $kunde["username"];
                $_SESSION["kunden_email"] = $kunde["email"];

                header("Location: index.html");
                exit;
            }

        } else {

            $error = "Benutzername oder Passwort ist falsch.";
        }

    } else {

        $error = "Benutzername oder Passwort ist falsch.";
    }


    $stmt->close();
}

?>

<!DOCTYPE html>

<html lang="de">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EasyOrder - Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>


<body>


<section class="food-search text-center">

    <div class="container">

        <h1>EasyOrder</h1>

        <p>Willkommen zurück!</p>

    </div>

</section>


<section>

    <div class="container">

        <h2>Anmelden</h2>


        <?php if ($error !== "") : ?>

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>


        <form method="POST"
              action="login.php">


            <!-- Benutzername -->

            <div class="order-label">
                Benutzername
            </div>

            <input
                type="text"
                name="username"
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


            <br>


            <input
                type="submit"
                value="Anmelden"
                class="btn btn-primary">


        </form>


        <p style="margin-top: 20px;">

            Noch kein Konto?

            <a href="register.php">
                Jetzt registrieren
            </a>

        </p>


        <p>

            <a href="index.html">
                Zurück zur Startseite
            </a>

        </p>


    </div>

</section>


</body>

</html>