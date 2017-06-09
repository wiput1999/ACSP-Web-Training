<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>จัดการผู้ใช้</title>
  </head>
  <body>
<h1>จัดการผู้ใช้</h1>
<?php
if (array_key_exists('login', $_SESSION)) {
  $id = $_SESSION['login'];

  $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id');

  $stmt->bindValue(':id', $id, PDO::PARAM_INT);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();

    if ($user['admin'] == 1) {
?>
<a href="index.php">หน้าหลัก</a><br />
<br />
<?php
      if (array_key_exists('send', $_POST)) {
        $admin = $_POST['admin'];

        $stmt = $conn->prepare('UPDATE member SET admin = :admin WHERE id = :id');

        foreach ($admin as $id => $value) {
          $stmt->bindValue(':id', $id, PDO::PARAM_INT);
          $stmt->bindValue(':admin', $value, PDO::PARAM_INT);

          $stmt->execute();
        }
      }
?>
<form action="admin.php" method="post">
  <table border="1">
    <tr>
      <th>ID</th>
      <th>อีเมล์</th>
      <th>ชื่อผู้ใช้</th>
      <th>ชื่อจริง</th>
      <th>Admin</th>
    </tr>
<?php
      $stmt = $conn->query('SELECT * FROM member');

      while($row = $stmt->fetch()) {
?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['email']; ?></td>
      <td><?php echo $row['username']; ?></td>
      <td><?php echo $row['name']; ?></td>
      <td>
        <input type="hidden" name="admin[<?php echo $row['id']; ?>]" value="0" />
        <input type="checkbox" name="admin[<?php echo $row['id']; ?>]" value="1"<?php if ($row['admin'] == 1): ?> checked<?php endif; ?> />
      </td>
    </tr>
<?php
      }
?>
  </table>
  <br />
  <button type="submit" name="send" value="admin">แก้ไข</button>
</form>
<?php
    } else {
?>
ไม่มีสิทธ์ใช้งานระบบนี้
<?php
    }
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
