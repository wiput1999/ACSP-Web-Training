<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>จัดการสินค้า</title>
  </head>
  <body>
<h1>จัดการสินค้า</h1>
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
<a href="index.php">หน้าหลัก</a> | <a href="item_add.php">เพิ่มสินค้า</a><br />
<br />
<?php
      if (array_key_exists('send', $_POST)) {
        if (array_key_exists('delete', $_POST)) {
          $delete = $_POST['delete'];

          $stmt = $conn->prepare('DELETE FROM item WHERE id = :id');

          foreach ($delete as $value) {
            $stmt->bindValue(':id', $value, PDO::PARAM_INT);

            $stmt->execute();
          }
        } else {
?>
กรุณาเลือกสินค้าที่จะลบ<br />
<br />
<?php
        }
      }
?>
<form action="item.php" method="post">
  <table border="1">
    <tr>
      <th>ID</th>
      <th>ชื่อสินค้า</th>
      <th>รายละเอียด</th>
      <th>ราคา</th>
      <th>ลบ</th>
    </tr>
<?php
      $stmt = $conn->query('SELECT * FROM item');

      while($row = $stmt->fetch()) {
?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['detail']; ?></td>
      <td><?php echo $row['price']; ?></td>
      <td>
        <input type="checkbox" name="delete[]" value="<?php echo $row['id']; ?>" />
      </td>
    </tr>
<?php
      }
?>
  </table>
  <br />
  <button type="submit" name="send" value="item">ลบ</button>
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
