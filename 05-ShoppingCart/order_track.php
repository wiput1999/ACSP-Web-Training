<?php
require 'database.php';

session_start();

$order_id = $_GET['id'];

$stmt = $conn->prepare('SELECT * FROM `order` WHERE `id` = :id');

$stmt->bindValue(':id', $order_id, PDO::PARAM_INT);

$stmt->execute();

if ($stmt->rowCount() > 0) {
  $order_info = $stmt->fetch();
?>
หมายเลขคำสั่งซื้อที่ #<?php echo $order_info['id']; ?><br />
<br />
สถานะ <?php echo $order_status_text[$order_info['status']]; ?>
<?php
} else {
?>
ไม่พบคำสั่งซื้อดังกล่าว
<?php
}
?>
