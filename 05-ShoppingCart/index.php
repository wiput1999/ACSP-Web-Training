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
<a href="admin.php">จัดการผู้ใช้</a><br />
<br />
<a href="item.php">จัดการสินค้า</a><br />
<a href="order.php">จัดการคำสั่งซื้อ</a>
<?php
    } else {
?>
เป็นผู้ใช้<br />
<br />
การสั่งซื้อ<br />
<br />
<table border="1" cellpadding="5">
  <tr>
    <th>ID</th>
    <th>รายการสั่งซื้อ</th>
    <th>สถานะ</th>
  </tr>
<?php
      $stmt = $conn->prepare('SELECT * FROM `order` WHERE member_id = :id');

      $stmt->bindValue(':id', $id, PDO::PARAM_INT);

      $stmt->execute();

      // TO-DO: use SQL JOIN instead of multiple query
      $sub_stmt_user_info = $conn->prepare('SELECT * FROM member WHERE id = :id');

      $sub_stmt_order_cart = $conn->prepare('SELECT * FROM order_cart WHERE order_id = :order_id');

      while($row = $stmt->fetch()) {

        $sub_stmt_user_info->bindValue(':id', $row['member_id'], PDO::PARAM_INT);

        $sub_stmt_user_info->execute();

        $user_info = $sub_stmt_user_info->fetch();

        $sub_stmt_order_cart->bindValue(':order_id', $row['id'], PDO::PARAM_INT);

        $sub_stmt_order_cart->execute();
  ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td>
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

            while($row_inner = $sub_stmt_order_cart->fetch()) {
              $sub_stmt->bindValue(':id', $row_inner['item_id'], PDO::PARAM_INT);

              $sub_stmt->execute();

              $item_info = $sub_stmt->fetch();
        ?>
          <tr>
            <td><?php echo $item_info['name']; ?></td>
            <td><?php echo $item_info['price']; ?></td>
            <td><?php echo $row_inner['amount']; ?></td>
          </tr>
        <?php
              $count += 1;

              $total_amount += (int) $row_inner['amount'];
              $total_price += (int) $row_inner['amount'] * (int) $item_info['price'];
            }
        ?>
          <tr>
            <td colspan="3">
              สินค้า <?php echo $count; ?> รายการ ทั้งหมด <?php echo $total_amount; ?> ชื้น ราคารวม <?php echo $total_price; ?> บาท
            </td>
          </tr>
        </table>
      </td>
      <td><?php echo $order_status_text[$row['status']]; ?></td>
    </tr>
<?php
    }
?>
</table><br />

เลือกซื้อสินค้า<br />
<br />
<table border="1" cellpadding="5">
  <tr>
    <th>ชื่อสินค้า</th>
    <th>รายละเอียด</th>
    <th>ราคา</th>
    <th>สั่งซื้อ</th>
  </tr>
<?php
      $stmt = $conn->query('SELECT * FROM item');

      while ($row = $stmt->fetch()) {
?>
  <tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['detail']; ?></td>
    <td><?php echo $row['price']; ?></td>
    <td>
      <form action="cart.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
        <input type="number" name= "amount" value="1" size="1" />
        <button type="submit" name="send" value="add_cart">สั่งซื้อ</button>
      </form>
    </td>
  </tr>
<?php
      }
?>
</table>
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
