<?php

session_start();

// Initialize Errors session
$_SESSION["errors"] = array();

if (array_key_exists('send', $_POST)) {
    require('config.inc.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = trim($username);
    $password = trim($password);

    if ($username == '') array_push($_SESSION["errors"], 'You must enter username');
    if ($password == '') array_push($_SESSION["errors"], 'You must enter password');

    if (count($_SESSION["errors"]) == 0) {
        $password = hash('sha512', $password);

        $stmt = $conn->prepare('SELECT * FROM member WHERE username = :username AND password = :password');

        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            // Set user id as session
            $_SESSION['login'] = $user['id'];

            // Set session to display success login
            $_SESSION['success'] = true;

            // Clear Error
            $_SESSION["errors"] = array();

            header('Location: index.php');
        } else {
            array_push($_SESSION["errors"], 'Username or Password is incorrect!');

            header('Location: index.php');
        }
    }
    else {
        header('Location: index.php');
        exit();
    }
}
else {
    array_push($_SESSION["errors"], 'Incorrect sending method!');

    header('Location: index.php');
}
