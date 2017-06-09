<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>หน้าหลัก</title>
  </head>
  <body>
<h1>หน้าหลัก</h1>
<?php
if (array_key_exists('login', $_SESSION)) {
  $id = $_SESSION['login'];

  $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id');

  $stmt->bindValue(':id', $id, PDO::PARAM_INT);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();
?>
ยินดีต้อนรับ <?php echo $user['name']; ?> (<?php echo $user['email']; ?>)<br />
<?php
    if ($user['admin'] == 1) {
?>
เป็นผู้ดูแลระบบ<br />
<br />
<a href="admin.php">จัดการผู้ใช้</a>
<?php
    } else {
?>
เป็นผู้ใช้
<?php
    }
?>
<br />
<br />
<a href="logout.php">ออกจากระบบ</a>
<?php
  } else {
?>
เกิดข้อผิดพลาดในระบบ กรุณาติดต่อผู้ดูแลระบบ
<?php
  }
} else {
?>
กรุณา <a href="login.php">เข้าสู่ระบบ</a>
<?php
}
?>
  </body>
</html>
