<?php

session_start();

// Initialize Errors session
$_SESSION["errors"] = array();

if (array_key_exists('send', $_POST)) {
    require('config.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    if ($password != $cpassword) array_push($_SESSION["errors"], 'Password and confirm not match!');
    if ($username == '') array_push($_SESSION["errors"], 'You must enter username');
    if ($password == '') array_push($_SESSION["errors"], 'You must enter password');
    if ($firstname == '') array_push($_SESSION["errors"], 'You must enter firstname');
    if ($lastname == '') array_push($_SESSION["errors"], 'You must enter lastname');
    if ($email == '') array_push($_SESSION["errors"], 'You must enter email');

    $username = trim($username);
    $password = trim($password);
    $firstname = trim($firstname);
    $lastname = trim($lastname);
    $email = trim($email);

    if (count($_SESSION["errors"]) == 0) {
        $password = hash('sha512', $password);

        $stmt = $conn->prepare('INSERT INTO member (email, username, password, firstname, lastname) VALUES (:email, :username, :password, :firstname, :lastname)');

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);

        $stmt->execute();

        $_SESSION['register'] = true;

        header('Location: index.php');
    }
    else {
        header('Location: register.php');
        exit();
    }
}
else {
    array_push($_SESSION["errors"], 'Incorrect sending method!');

    header('Location: index.php');
}
