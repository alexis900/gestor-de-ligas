<?php
require_once('includes/functions.php');
require_once('db.php');
session_start();

if (isSession()) {
    session_unset();
    session_destroy();
    header("Location: index.php");
} else {
    header("Location: index.php");
}

?>