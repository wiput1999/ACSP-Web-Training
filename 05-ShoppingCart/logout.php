<?php
session_start();

if (array_key_exists('login', $_SESSION)) {
  session_unset();
  session_destroy();
?>
ออกจากระบบแล้ว<br />
<br />
<a href="index.php">ดำเนินการต่อ</a>
<?php
}
?>
