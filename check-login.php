<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

if (isset($_SESSION["kunden_id"])) {

    echo json_encode([
        "logged_in" => true,
        "username" => $_SESSION["kunden_username"]
    ]);

} else {

    echo json_encode([
        "logged_in" => false,
        "username" => null
    ]);
}

?>