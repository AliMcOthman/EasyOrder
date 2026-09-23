<?php

session_start();


// =========================
// ADMIN LOGIN PRÜFEN
// =========================

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin-login.php");
    exit;
}


// =========================
// DATENBANKVERBINDUNG
// =========================

require_once "config.php";


// =========================
// CONTACT NACHRICHTEN LADEN
// =========================

$sql = "SELECT id, name, email, subject, message, created_at
        FROM contact_messages
        ORDER BY created_at DESC";

$result = $conn->query($sql);


// Anzahl der Nachrichten
$messageCount = 0;

if ($result) {

    $messageCount = $result->num_rows;
}

?>

<!DOCTYPE html>
<html lang="de">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>EasyOrder - Contact Messages</title>


    <!-- Main CSS -->
    <link rel="stylesheet"
          href="css/style.css">


    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>


<body>


<!-- =========================
     ADMIN CONTACT PAGE
     ========================= -->

<section class="admin-contact-page">

    <div class="container">


        <!-- Titel -->

        <h1 class="text-center">
            Contact Messages
        </h1>


        <!-- =========================
             TOP BAR
             ========================= -->

        <div class="contact-admin-top">


            <!-- Back Button -->

            <a href="admin.php"
               class="back-admin-button">

                <i class="fa-solid fa-arrow-left"></i>

                Back to Admin

            </a>


            <!-- Message Count -->

            <p class="contact-total">

                <strong>
                    Messages:
                </strong>

                <?php echo $messageCount; ?>

            </p>


        </div>


        <!-- =========================
             CONTACT TABLE
             ========================= -->

        <div class="contact-table-container">


            <?php

            if ($result && $result->num_rows > 0) {

            ?>

                <table class="contact-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>E-Mail</th>

                            <th>Subject</th>

                            <th>Message</th>

                            <th>Date</th>

                            <th>Aktion</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    while ($row = $result->fetch_assoc()) {

                    ?>


                        <tr>


                            <!-- ID -->

                            <td>

                                <?php

                                echo (int)$row["id"];

                                ?>

                            </td>


                            <!-- Name -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["name"]
                                );

                                ?>

                            </td>


                            <!-- E-Mail -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["email"]
                                );

                                ?>

                            </td>


                            <!-- Subject -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["subject"]
                                );

                                ?>

                            </td>


                            <!-- Message -->

                            <td class="contact-message-cell">

                                <?php

                                echo nl2br(
                                    htmlspecialchars(
                                        $row["message"]
                                    )
                                );

                                ?>

                            </td>


                            <!-- Date -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $row["created_at"]
                                );

                                ?>

                            </td>


                            <!-- Delete -->

                            <td>

                                <button
                                    type="button"
                                    class="delete-contact"
                                    data-id="<?php echo $row['id']; ?>">

                                    <i class="fa-solid fa-trash"></i>

                                </button>

                            </td>


                        </tr>


                    <?php

                    }

                    ?>


                    </tbody>

                </table>


            <?php

            } else {

            ?>


                <!-- Keine Nachrichten -->

                <div class="no-messages">

                    <i class="fa-regular fa-envelope"></i>

                    <p>
                        No contact messages found.
                    </p>

                </div>


            <?php

            }

            ?>


        </div>

    </div>

</section>



<!-- =========================
     JAVASCRIPT
     ========================= -->

<!-- jQuery -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<!-- Admin JavaScript -->

<script src="admin.js"></script>


</body>

</html>


<?php

$conn->close();

?>

