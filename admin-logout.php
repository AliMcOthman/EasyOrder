<?php

session_start();

// Session löschen
session_unset();
session_destroy();

// Zur Login-Seite zurückkehren
header("Location: admin-login.php");
exit;

?>