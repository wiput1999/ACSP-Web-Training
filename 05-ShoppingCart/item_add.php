<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>เพิ่มสินค้า</title>
  </head>
  <body>
<h1>เพิ่มสินค้า</h1>
<br />
<?php
if (array_key_exists('login', $_SESSION)) {
  $id = $_SESSION['login'];

  $stmt = $conn->prepare('SELECT * FROM member WHERE id = :id');

  $stmt->bindValue(':id', $id, PDO::PARAM_INT);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();

    if ($user['admin'] == 1) {
      $show = true;

      $errors = [];

      if (array_key_exists('send', $_POST)) {
        $name = $_POST['name'];
        $detail = $_POST['detail'];

        $price = $_POST['price'];

        $name = trim($name);
        $detail = trim($detail);

        $price = trim($price);

        if ($name == '') array_push($errors, 'กรุณากรอก ชื่อสินค้า');

        if ($price == '') {
          array_push($errors, 'กรุณากรอก ราคา');
        } else {
          if (!is_numeric($price)) {
            array_push($errors, 'กรุณากรอก ราคา เป็นตัวเลข');
          } else {
            $price = (int) $price;
          }
        }

        if (count($errors) == 0) {
          $stmt = $conn->prepare('INSERT INTO item (name, detail, price) VALUES (:name, :detail, :price)');

          $stmt->bindValue(':name', $name, PDO::PARAM_STR);
          $stmt->bindValue(':detail', $detail, PDO::PARAM_STR);

          $stmt->bindValue(':price', $price, PDO::PARAM_INT);

          $stmt->execute();

          $show = false;
        }
      }

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
<form action="item_add.php" method="POST">
  ชื่อสินค้า <input type="text" name="name"/><br/>
  รายละเอียด<br />
  <textarea name="detail"></textarea><br/>
  <br />
  ราคา <input type="text" name="price"/><br/>
  <br />
  <button type="submit" name="send" value="item_add">เพิ่มสินค้า</button>
</form>
<?php
      } else {
?>
เพิ่มสินค้าแล้ว<br />
<br />
<a href="item.php">กลับ</a>
<?php
      }
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
