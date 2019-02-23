<?php
require_once('includes/functions.php');
require_once('db.php');
session_start();

//Si la sesión está iniciada, se cerrará
if (isSession()) {
    session_unset();
    session_destroy();
    header("Location: index.php");
} else {
    header("Location: index.php");
}

?>