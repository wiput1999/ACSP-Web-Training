<?php
require 'database.php';

session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ตะกร้าสินค้า</title>
  </head>
  <body>
<h1>ตะกร้าสินค้า</h1>
<a href="index.php">เลือกสินค้า</a><br />
<br />
<?php
if (array_key_exists('login', $_SESSION)) {
  $id = $_SESSION['login'];

  $stmt = $conn->prepare('SELECT * FROM `member` WHERE `id` = :id');

  $stmt->bindValue(':id', $id, PDO::PARAM_INT);

  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();

    $show = true;

    if (array_key_exists('send', $_POST)) {
      $send_action = $_POST['send'];

      switch ($send_action) {
        case 'add_cart':

          $item_id = $_POST['id'];
          $amount = $_POST['amount'];

          $stmt = $conn->prepare('INSERT INTO `member_cart` (`item_id`, `amount`, `member_id`) VALUES (:item_id, :amount, :member_id)');

          $stmt->bindValue(':item_id', $item_id, PDO::PARAM_INT);
          $stmt->bindValue(':amount', $amount, PDO::PARAM_INT);

          $stmt->bindValue(':member_id', $id, PDO::PARAM_INT);

          $stmt->execute();

          break;

        case 'delete_cart':

          if (array_key_exists('delete', $_POST)) {
            $delete = $_POST['delete'];

            $stmt = $conn->prepare('DELETE FROM `member_cart` WHERE id = :id');

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

          break;

        case 'order_cart':

          $stmt = $conn->prepare('INSERT INTO `order` (`member_id`) VALUES (:member_id)');

          $stmt->bindValue(':member_id', $id, PDO::PARAM_INT);

          $stmt->execute();

          $order_id = $conn->lastInsertId();

?>
ขอบคุณที่สั่งสินค้ากับเรา<br />
<br />
สรุปคำสั่งซื้อ
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

    $stmt = $conn->prepare('SELECT * FROM `member_cart` WHERE `member_id` = :member_id');

    $stmt->bindValue(':member_id', $id, PDO::PARAM_INT);

    $stmt->execute();

    // TO-DO: use SQL JOIN instead of multiple query
    $sub_stmt = $conn->prepare('SELECT * FROM item WHERE id = :id');

    $sub_stmt_transfer = $conn->prepare('INSERT INTO `order_cart` (`item_id`, `amount`, `order_id`) VALUES (:item_id, :amount, :order_id)');

    $sub_stmt_transfer->bindValue(':order_id', $order_id, PDO::PARAM_INT);

    while($row = $stmt->fetch()) {
      $sub_stmt_transfer->bindValue(':item_id', $row['item_id'], PDO::PARAM_INT);
      $sub_stmt_transfer->bindValue(':amount', $row['amount'], PDO::PARAM_INT);

      $sub_stmt_transfer->execute();

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
<a href="order_track.php?id=<?php echo $order_id; ?>">ติดตามการสั่งซื้อ</a>
<br />
<br />
<?php
          $stmt = $conn->prepare('DELETE FROM `member_cart` WHERE `member_id` = :member_id');

          $stmt->bindValue(':member_id', $id, PDO::PARAM_INT);

          $stmt->execute();

          $show = false;

          break;
      }
    }

  if ($show) {
?>
<form action="cart.php" method="post">
  <table border="1">
    <tr>
      <th>ชื่อสินค้า</th>
      <th>ราคา</th>
      <th>ปริมาณ</th>
      <th>ลบ</th>
    </tr>
<?php
      $count = 0;

      $total_amount = 0;
      $total_price = 0;


      $stmt = $conn->prepare('SELECT * FROM `member_cart` WHERE `member_id` = :member_id');

      $stmt->bindValue(':member_id', $id, PDO::PARAM_INT);

      $stmt->execute();

      $sub_stmt = $conn->prepare('SELECT * FROM `item` WHERE `id` = :id');

      while($row = $stmt->fetch()) {

        $sub_stmt->bindValue(':id', $row['item_id'], PDO::PARAM_INT);

        $sub_stmt->execute();

        $item_info = $sub_stmt->fetch();
?>
    <tr>
      <td><?php echo $item_info['name']; ?></td>
      <td><?php echo $item_info['price']; ?></td>
      <td><?php echo $row['amount']; ?></td>
      <td>
        <input type="checkbox" name="delete[]" value="<?php echo $row['id']; ?>" />
      </td>
    </tr>
<?php
        $count += 1;

        $total_amount += (int) $row['amount'];
        $total_price += (int) $row['amount'] * (int) $item_info['price'];
      }
?>
    <tr>
      <td colspan="4">
        สินค้า <?php echo $count; ?> รายการ ทั้งหมด <?php echo $total_amount; ?> ชื้น ราคารวม <?php echo $total_price; ?> บาท
      </td>
    </tr>
  </table>
  <br />
  <button type="submit" name="send" value="delete_cart">ลบสินค้า</button><br />
  <br />
  <br />
  <button type="submit" name="send" value="order_cart">สั่งซื้อ</button>
</form>
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
