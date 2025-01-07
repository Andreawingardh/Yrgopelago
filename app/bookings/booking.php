<?php

declare(strict_types=1);

require '../autoload.php';

session_start();

$bookingData = getBookingData($_POST);


$availability = checkAvailability($bookingData);

if ($availability > 0) {
    $_SESSION['errors'][] = 'Your dates are not available';
    redirect(BASE_URL . '/index.php#hotel-booking');
}

$bookingData['total-cost'] = getTotalCost($bookingData);


if (isValidUuid($bookingData['transfer-code'])) {
    $transferCodeResult = checkTransferCode($bookingData);
}   else {
    $_SESSION['errors'][] = 'Your transfer code is not valid';
    redirect(BASE_URL . '/index.php#hotel-booking');

}

if (isset($transferCodeResult['status']) && $transferCodeResult['status'] === 'success') {
    depositTransferCode($bookingData['transfer-code']);

    sendBookingData($bookingData);
    createJsonReceipt($bookingData);

}




