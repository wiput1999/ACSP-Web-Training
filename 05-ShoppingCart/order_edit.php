<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>แก้ไขคำสั่งซื้อ</title>
  </head>
  <body>
<h1>แก้ไขคำสั่งซื้อ</h1>
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
<a href="order.php">จัดการคำสั่งซื้อ</a><br />
<br />
<?php
      $order_id = $_GET['id'];

      if (array_key_exists('send', $_POST)) {
        $status = $_POST['status'];

        $stmt = $conn->prepare('UPDATE `order` SET `status` = :status WHERE `id` = :id');

        $stmt->bindValue(':id', $order_id);

        $stmt->bindValue(':status', $status);

        $stmt->execute();
      }

      $stmt = $conn->prepare('SELECT * FROM `order` WHERE `id` = :id');

      $stmt->bindValue(':id', $order_id);

      $stmt->execute();

      $order_info = $stmt->fetch();

      // TO-DO: use SQL JOIN instead of multiple query
      $sub_stmt_user_info = $conn->prepare('SELECT * FROM member WHERE id = :id');

      $sub_stmt_user_info->bindValue(':id', $order_info['member_id'], PDO::PARAM_INT);

      $sub_stmt_user_info->execute();

      $user_info = $sub_stmt_user_info->fetch();

      $sub_stmt_order_cart = $conn->prepare('SELECT * FROM order_cart WHERE order_id = :order_id');

      $sub_stmt_order_cart->bindValue(':order_id', $order_id, PDO::PARAM_INT);

      $sub_stmt_order_cart->execute();
?>
<table border="1">
  <tr>
    <th>ชื่อสินค้า</th>
    <th>ราคา</th>
    <th>ปริมาณ</th>
  </tr>
<?php
    $count = 0;

    $total_amount = 0;
    $total_price = 0;

    // TO-DO: use SQL JOIN instead of multiple query
    $sub_stmt = $conn->prepare('SELECT * FROM item WHERE id = :id');

    while($row = $sub_stmt_order_cart->fetch()) {
      $sub_stmt->bindValue(':id', $row['item_id'], PDO::PARAM_INT);

      $sub_stmt->execute();

      $item_info = $sub_stmt->fetch();
?>
  <tr>
    <td><?php echo $item_info['name']; ?></td>
    <td><?php echo $item_info['price']; ?></td>
    <td><?php echo $row['amount']; ?></td>
  </tr>
<?php
      $count += 1;

      $total_amount += (int) $row['amount'];
      $total_price += (int) $row['amount'] * (int) $item_info['price'];
    }
?>
  <tr>
    <td colspan="3">
      สินค้า <?php echo $count; ?> รายการ ทั้งหมด <?php echo $total_amount; ?> ชื้น ราคารวม <?php echo $total_price; ?> บาท
    </td>
  </tr>
</table>
<br />
<form action="" method="post">
<?php foreach ($order_status_text as $key => $text): ?>
  <input type="radio" name="status" value="<?php echo $key; ?>"<?php if ($order_info['status'] == $key): ?> checked<?php endif; ?> /> <?php echo $text; ?><br />
<?php endforeach; ?>
  <button type="submit" name="send" value="edit_order">แก้ไข</button>
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
