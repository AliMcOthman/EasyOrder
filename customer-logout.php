<?php

session_start();

// Kunden-Session löschen
session_unset();
session_destroy();

// Zur Startseite zurückkehren
header("Location: index.html");
exit;

?>