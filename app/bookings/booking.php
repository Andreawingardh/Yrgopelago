<?php

declare(strict_types=1);

require '../autoload.php';

// session_unset();
// session_start();

if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $message) {
        echo $message;
    }
}

$bookingData = getBookingData($_POST);

echo "<pre>";
var_dump($bookingData);
echo "</pre>";

$availability = checkAvailability($bookingData);

echo 'Availability: ' . $availability . "<br>";

$bookingData['total-cost'] = getTotalCost($bookingData);

echo 'Total cost: ' . $bookingData['total-cost'];

if (isValidUuid($bookingData['transfer-code'])) {
    $transferCodeResult = checkTransferCode($bookingData);
    var_dump($transferCodeResult);
} else {
    $_SESSION['messages'][] = 'Your transfer code is not valid';
    header('Location: /');
}

if (isset($transferCodeResult['status']) && $transferCodeResult['status'] === 'success') {
    depositTransferCode($bookingData['transfer-code']);
    $_SESSION['messages'][] = "Your transfercode has been deposited";

    sendBookingData($bookingData);
    createJsonReceipt($bookingData);
}
