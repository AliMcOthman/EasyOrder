<?php

session_start();

require_once "config.php";


// Wenn der Admin bereits angemeldet ist
if (isset($_SESSION["admin_id"])) {

    header("Location: admin.php");
    exit;
}


// Login-Aktion
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];


    // Admin suchen
    $sql = "SELECT id, username, password
            FROM admin_users
            WHERE username = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $username);

    $stmt->execute();

    $result = $stmt->get_result();


    // Admin gefunden
    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();


        // Passwort prüfen
        if (password_verify(
            $password,
            $admin["password"]
        )) {


            // Session speichern
            $_SESSION["admin_id"] = $admin["id"];

            $_SESSION["admin_username"] =
                $admin["username"];


            // Zur Admin-Seite weiterleiten
            header("Location: admin.php");
            exit;


        } else {

            $error =
                "Benutzername oder Passwort ist falsch.";
        }


    } else {

        $error =
            "Benutzername oder Passwort ist falsch.";
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

    <title>EasyOrder - Admin Login</title>


    <link rel="stylesheet"
          href="css/style.css">

</head>


<body>


<section class="food-search text-center">

    <div class="container">

        <h1>EasyOrder Admin</h1>

        <p>Administrator Login</p>

    </div>

</section>



<section>

    <div class="container">

        <h2>Login</h2>


        <?php if (isset($error)) : ?>

            <p>
                <?php echo $error; ?>
            </p>

        <?php endif; ?>


        <form method="POST"
              action="admin-login.php">


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


            <!-- Login Button -->

            <input
                type="submit"
                value="Anmelden"
                class="btn btn-primary">


        </form>

    </div>

</section>


</body>

</html>