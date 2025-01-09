<?php

declare(strict_types=1);
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php';
// session_start();

?>


<?php
require __DIR__ . '/views/header.php'; ?>
<hr>
<?php require __DIR__ . '/views/rooms.php'; ?>
<hr>
<?php require __DIR__ . '/views/atm.php'; ?>
<hr>
<?php require __DIR__ . '/views/form.php'; ?>
<hr>
<?php require __DIR__ . '/views/footer.php';
?>