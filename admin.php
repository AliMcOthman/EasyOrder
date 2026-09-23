<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin-login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">

    <title>EasyOrder - Admin</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>



<section class="food-search text-center">
     <!-- Abmelden -->
     <a href="admin-logout.php" class="admin-logout">
       <i class="fa-solid fa-right-from-bracket"></i>
                      Abmelden
     </a>
    <div class="container">

        <h1>EasyOrder Admin</h1>
        <p>
            Willkommen,
            <strong><?php echo htmlspecialchars($_SESSION["admin_username"]); ?></strong> 👋
        </p>
        <p>Bestellungen</p>



    </div>


</section>


<section>

    <div class="container">

        <h2>Alle Bestellungen</h2>

        <button id="refresh-orders">
            <i class="fa-solid fa-rotate"></i>
            Aktualisieren
        </button>


        <a href="admin_contact.php" class="btn btn-primary admin-contact-button">

            <i class="fa-solid fa-envelope"></i>

            Contact Messages

            <span id="contact-count" class="contact-count">
                0
            </span>

        </a>



        <select id="status-filter">
            <option value="Alle">Alle Bestellungen</option>
            <option value="Neu">Neu</option>
            <option value="In Bearbeitung">In Bearbeitung</option>
            <option value="Erledigt">Erledigt</option>
        </select>


        <div class="admin-stats">

            <div class="stat-box">

                <h3>Bestellungen</h3>

                <p id="total-orders">0</p>

            </div>


            <div class="stat-box">

                <h3>Gesamtumsatz</h3>

                <p id="total-sales">0.00 €</p>

            </div>

        </div>


        <div id="orders-container"></div>

    </div>

</section>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="admin.js"></script>

</body>

</html>