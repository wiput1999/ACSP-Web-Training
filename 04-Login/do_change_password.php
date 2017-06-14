<?php

session_start();

// Initialize Errors session
$_SESSION["errors"] = array();

if (array_key_exists('send', $_POST)) {
    require('config.inc.php');

    $id = $_SESSION['login'];

    $password = $_POST['cpassword'];
    $newpassword = $_POST['newpassword'];
    $cnewpassword = $_POST['cnewpassword'];
    $password = trim($password);
    $newpassword = trim($newpassword);
    $cnewpassword = trim($cnewpassword);

    if ($newpassword != $cnewpassword) {
        array_push($_SESSION["errors"], 'New Password and Confirm Password not match!');

        header('Location: change_password.php');
        exit();
    }

    if (count($_SESSION["errors"]) == 0) {
        $password = hash('sha512', $password);

        $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id AND password = :password');

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            try {
                $newpassword = hash('sha512', $newpassword);

                $stmt = $conn->prepare("UPDATE member SET password = :password WHERE id = :id");

                $stmt->bindValue(':password', $newpassword, PDO::PARAM_STR);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);

                $stmt->execute();

                $_SESSION['success'] = true;

                header('Location: change_password.php');

            } catch(PDOException $e) {
                array_push($_SESSION["errors"], 'Something wrong!');

                header('Location: change_password.php');
            }

        } else {
            array_push($_SESSION["errors"], 'Current password is incorrect!');

            header('Location: change_password.php');
        }
    }

} else {
    array_push($_SESSION["errors"], 'Incorrect sending method!');

    header('Location: edit_profile.php');
}

?>
