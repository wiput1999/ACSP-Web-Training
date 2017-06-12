<?php
require 'database.php';

session_start();

$show = true;

$errors = [];

if (array_key_exists('send', $_POST)) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $username = trim($username);
  $password = trim($password);

  if ($username == '') array_push($errors, 'กรุณากรอก ชื่อผู้ใช้');
  if ($password == '') array_push($errors, 'กรุณากรอก รหัสผ่าน');

  if (count($errors) == 0) {
    $password = hash('sha512', $password);

    $stmt = $conn->prepare('SELECT * FROM member WHERE username = :username AND password = :password');

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch();

      $show = false;
    } else {
      array_push($errors, 'ชื่อผู้ใช้ หรือ รหัสผ่าน ผิด');
    }
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบ</title>
  </head>
  <body>
<h1>เข้าสู่ระบบ</h1>
<?php
if ($show) {
  if ($errors) {
?>
เกิดข้อผิดพลาด<br />
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
<form action="login.php" method="POST">
  ชื่อผู้ใช้ <input type="text" name="username"/><br/>
  รหัสผ่าน <input type="password" name="password"/><br/>
  <br/>
  <button type="submit" name="send" value="login">เข้าสู่ระบบ</button> <a href="register.php">สมัครสมาชิก</a>
</form>
<?php
} else {
  $_SESSION['login'] = $user['id'];
?>
ยินดีต้อนรับ <?php echo $user['name']; ?><br />
<br />
<a href="index.php">ดำเนินการต่อ</a>
<?php
}
?>
  </body>
</html>
