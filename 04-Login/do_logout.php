<?php
session_start();

if (array_key_exists('login', $_SESSION)) {
    session_unset();

    $_SESSION['logout'] = true;
    header("Location: index.php");
}
?>
