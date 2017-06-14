<?php

session_start();

if (count($_SESSION["errors"]) != 0) {
?>
<ul>
    <?php
    foreach ($_SESSION["errors"] as $error) {
        ?>
        <li><?php echo $error; ?></li>
        <?php
    }
    ?>
</ul>
<?php
} else {
    echo "Success!";
}
?>
