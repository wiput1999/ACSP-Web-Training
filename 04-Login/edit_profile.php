<?php
session_start();

if (!isset($_SESSION['login'])) {
    array_push($_SESSION["errors"], 'You must login first!');
    header('Location: index.php');
    exit();
}

require 'config.inc.php';

$id = $_SESSION['login'];

$stmt = $conn->prepare('SELECT * FROM member WHERE id = :id');

$stmt->bindValue(':id', $id, PDO::PARAM_INT);

$stmt->execute();

if ($stmt->rowCount() > 0) {
    $data = $stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="ACSP Training Simple Login System">
    <meta name="author" content="Wiput & CasperX">
    <link rel="icon" href="favicon.ico">

    <title>Simple Shop</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Custom Style -->
    <link href="assets/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">ACSP Training</a>
            </div>
            <div id="main-nav" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome, <?=$data['firstname']?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="view_profile.php">View Profile</a></li>
                            <li><a href="edit_profile.php">Edit Profile</a></li>
                            <li><a href="change_password.php">Change Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="do_logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>

    <div class="container">
        <?php
            if(count($_SESSION["errors"]) != 0) {
                // Display error!
                foreach ($_SESSION["errors"] as $error) {
                    echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
                }
                // Clear up error!
                $_SESSION["errors"] = array();
            }
            if($_SESSION['success']) {
                echo "<div class='alert alert-success' role='alert'>Edit success!</div>";
                unset($_SESSION['success']);
            }
        ?>
        <div class="row">
            <h1>Edit Profile</h1>
            <hr>
            <form action="do_edit_profile.php" method="POST" class="form-horizontal">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">Username : </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" placeholder="Username" value="<?=$data['username']?>" aria-describedby="usernameHelp" disabled>
                        <span id="usernameHelp" class="help-block">Username cannot be changed.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="firstname" class="col-sm-2 control-label">Firstname : </label>
                    <div class="col-sm-10">
                        <input type="text" name="firstname" class="form-control" id="firstname" placeholder="Firstname" value="<?=$data['firstname']?>" aria-describedby="firstnameHelp">
                        <span id="firstnameHelp" class="help-block">Username cannot be changed.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">Lastname : </label>
                    <div class="col-sm-10">
                        <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Lastname" value="<?=$data['lastname']?>" aria-describedby="lastnameHelp">
                        <span id="lastnameHelp" class="help-block">Username cannot be changed.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">E-Mail : </label>
                    <div class="col-sm-10">
                        <input type="text" name="email" class="form-control" id="email" placeholder="E-Mail" value="<?=$data['email']?>" aria-describedby="emailHelp">
                        <span id="emailHelp" class="help-block">Username cannot be changed.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cpassword" class="col-sm-2 control-label">Current password : </label>
                    <div class="col-sm-10">
                        <input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="Current Password" aria-describedby="cpasswordHelp">
                        <span id="cpasswordHelp" class="help-block">Enter current password to confirm your identity.</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="send" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>

        <hr>

        <footer>
            <p>&copy; 2017 ACSP Training</p>
        </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
