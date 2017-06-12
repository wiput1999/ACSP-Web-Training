<?php
require 'database.php';

$show = true;

$errors = [];

if (array_key_exists('send', $_POST)) {
  $email = $_POST['email'];

  $username = $_POST['username'];
  $password = $_POST['password'];

  $name = $_POST['name'];

  $email = trim($email);

  $username = trim($username);
  $password = trim($password);

  $name = trim($name);

  if ($email == '') array_push($errors, 'กรุณากรอก อีเมล์');

  if ($username == '') {
    array_push($errors, 'กรุณากรอก ชื่อผู้ใช้');
  } else {
    if (strlen($username) < 4) {
      array_push($errors, 'ชื่อผู้ใช้ ต้องยาว 4 ตัวขึ้นไป');
    }
  }

  if ($password == '') {
    array_push($errors, 'กรุณากรอก รหัสผ่าน');
  } else {
    if (strlen($password) < 8) {
      array_push($errors, 'รหัสผ่าน ต้องยาว 8 ตัวขึ้นไป');
    }
  }

  if ($name == '') array_push($errors, 'กรุณากรอก ชื่อจริง');

  if (count($errors) == 0) {
    $stmt = $conn->prepare('SELECT * FROM member WHERE email = :email');

    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) array_push($errors, 'อีเมล์ ซ้ำ');
  }

  if (count($errors) == 0) {
    $stmt = $conn->prepare('SELECT * FROM member WHERE username = :username');

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) array_push($errors, 'ชื่อผู้ใช้ ซ้ำ');
  }

  if (count($errors) == 0) {
    $password = hash('sha512', $password);

    $stmt = $conn->prepare('INSERT INTO member (email, username, password, name) VALUES (:email, :username, :password, :name)');

    $stmt->bindValue(':email', $email, PDO::PARAM_STR);

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);

    $stmt->bindValue(':name', $name, PDO::PARAM_STR);

    $stmt->execute();

    $show = false;
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>สมัครสมาชิก</title>
  </head>
  <body>
<h1>สมัครสมาชิก</h1>
<?php
if ($show) {
  if ($errors) {
?>
<ul>
<?php
    foreach ($errors as $error) {
?>
  <li><?php echo $error; ?></li>
<?php
    }
  ?>
</ul>
  <?php
  }
?>
<form action="register.php" method="POST">
  อีเมล์ <input type="text" name="email"/><br/>
  <br />
  ชื่อผู้ใช้ <input type="text" name="username"/><br/>
  รหัสผ่าน <input type="password" name="password"/><br/>
  <br />
  ชื่อจริง <input type="text" name="name"/><br/>
  <br/>
  <button type="submit" name="send" value="register">สมัครสมาชิก</button>
</form>
<?php
} else {
?>
ยินดีต้อนรับ <?php echo $name; ?><br />
<br />
<a href="login.php">ดำเนินการต่อ</a>
<?php
}
?>
  </body>
</html>
