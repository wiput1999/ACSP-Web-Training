<?php
require 'config.inc.php';

session_start();

if (isset($_SESSION['login'])) {
    $id = $_SESSION['login'];

    $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id');

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch();
    }
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
                    <li class="active"><a href="index.php">Home <span class="sr-only">(current)</span></a></li>
                    <?php if (!isset($_SESSION['login'])) { ?>
                        <li><a href="register.php">Register</a></li>
                    <?php } ?>
                </ul>
                <?php if (!isset($_SESSION['login'])) { ?>
                    <!-- If user not logged in -->
                    <form action="do_auth.php" method="POST" class="navbar-form navbar-right">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" class="form-control">
                        </div>
                        <button type="submit" name="send" class="btn btn-success">Sign in</button>
                    </form>
                <?php } else { ?>
                    <!-- If user logged in -->
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
                <?php } ?>
            </div><!--/.navbar-collapse -->
        </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="container">
            <h1>Hi, <?php echo (isset($_SESSION['login']) ? $data['firstname'] : "Guest"); ?>!</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>

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
                echo "<div class='alert alert-success' role='alert'>Login success!</div>";
                unset($_SESSION['success']);
            }
            if($_SESSION['register']) {
                echo "<div class='alert alert-success' role='alert'>Register completed!</div>";
                unset($_SESSION['register']);
            }
            if($_SESSION['logout']) {
                echo "<div class='alert alert-success' role='alert'>Logout success! See you again!</div>";
                unset($_SESSION['logout']);
            }
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://lorempixel.com/640/480" alt="Product image">
                    <div class="caption">
                        <h3>Thumbnail label</h3>
                        <p>Test description</p>
                        <p><a href="#" class="btn btn-success" role="button">Add to cart</a> <a href="#" class="btn btn-default" role="button">More details</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://lorempixel.com/640/480" alt="Product image">
                    <div class="caption">
                        <h3>Thumbnail label</h3>
                        <p>Test description</p>
                        <p><a href="#" class="btn btn-success" role="button">Add to cart</a> <a href="#" class="btn btn-default" role="button">More details</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thumbnail">
                    <img src="http://lorempixel.com/640/480" alt="Product image">
                    <div class="caption">
                        <h3>Thumbnail label</h3>
                        <p>Test description</p>
                        <p><a href="#" class="btn btn-success" role="button">Add to cart</a> <a href="#" class="btn btn-default" role="button">More details</a></p>
                    </div>
                </div>
            </div>
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
