<?php

declare(strict_types=1);

// // Start the session engines.
// session_start();

// Set the default timezone to Coordinated Universal Time.
date_default_timezone_set('UTC');

// Set the default character encoding to UTF-8.
mb_internal_encoding('UTF-8');

// Include the helper functions.
require __DIR__ . '/functions.php';

//Create message array
$_SESSON['messages'] = [];

//Create error array 
$_SESSION['errors'] = [];

//Define base URL
define('BASE_URL', '/clown-island');