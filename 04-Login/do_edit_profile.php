<?php

session_start();

// Initialize Errors session
$_SESSION["errors"] = array();

if (array_key_exists('send', $_POST)) {
    require('config.inc.php');

    $id = $_SESSION['login'];

    $password = $_POST['cpassword'];
    $password = trim($password);

    if (count($_SESSION["errors"]) == 0) {
        $password = hash('sha512', $password);

        $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id AND password = :password');

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            try {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];

                $stmt = $conn->prepare("UPDATE member SET firstname = :firstname, lastname= :lastname , email = :email WHERE id = :id");

                $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
                $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
                $stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);

                $stmt->execute();

                $_SESSION['success'] = true;
                
                header('Location: edit_profile.php');

            } catch(PDOException $e) {
                array_push($_SESSION["errors"], 'Something wrong!');

                header('Location: edit_profile.php');
            }

        } else {
            array_push($_SESSION["errors"], 'Current password is incorrect!');

            header('Location: edit_profile.php');
        }
    }

} else {
    array_push($_SESSION["errors"], 'Incorrect sending method!');

    header('Location: edit_profile.php');
}

?>
