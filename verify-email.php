<?php

require_once "config.php";

$token = $_GET["token"] ?? "";

if ($token === "") {
    die("Ungültiger Bestätigungslink.");
}

$sql = "SELECT id
        FROM kunden
        WHERE verification_token = ?
        AND email_verified = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $kunde = $result->fetch_assoc();
    $kundenId = $kunde["id"];

    $sql = "UPDATE kunden
            SET email_verified = 1,
                verification_token = NULL
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kundenId);
    $stmt->execute();

    $message = "E-Mail-Adresse wurde erfolgreich bestätigt.";
    $success = true;

} else {

    $message = "Ungültiger oder bereits verwendeter Bestätigungslink.";
    $success = false;
}

$stmt->close();

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>E-Mail-Bestätigung - EasyOrder</title>


    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .message-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #15d4ed;
            margin-bottom: 15px;
        }

        p {
            font-size: 16px;
            color: #555;
        }

    </style>

</head>

<body>

<div class="message-box">

    <?php if ($success): ?>

        <h2>Erfolgreich bestätigt!</h2>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

        <p>
            Sie werden in Kürze zu EasyOrder weitergeleitet...
        </p>

        <script>
            setTimeout(function () {
                window.location.href = "index.html";
            }, 2000);
        </script>

    <?php else: ?>

        <h2>Fehler</h2>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

</div>

</body>

</html>

