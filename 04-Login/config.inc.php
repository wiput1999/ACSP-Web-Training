<?php
$db_type = 'mysql';

$db_host = 'localhost';

$db_name = 'member';

$db_username = 'web';
$db_password = 'KsOOzTpQ0JsudbvX';

$db_dsn = $db_type . ':' . ('host' . '=' . $db_host . ';' . 'charset' . '=' . 'utf8mb4');

$conn = new PDO($db_dsn, $db_username, $db_password);

$conn->exec('USE ' . $db_name);
