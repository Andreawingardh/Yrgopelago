<?php

declare(strict_types=1);
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/global.css">
    <link rel="stylesheet" href="/assets/styles/calendar.css">
    <title>Hotel</title>
</head>
<body>
<?php    
require __DIR__ . '/calendar.php';
require __DIR__ . '/atm.php';
require __DIR__ . '/form.php';
?>

<script src="/assets/scripts/script.js"></script>
</body>
</html>