<?php

declare(strict_types=1);

require __DIR__ .'app/autoload.php';

getBooking($_POST);

echo "<pre>";
var_dump($_POST);

