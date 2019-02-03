<?php
require_once('includes/functions.php');
require_once('db.php');
session_start();

if (isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
} else {
    header("Location: index.php");
}

?>