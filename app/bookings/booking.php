<?php

declare(strict_types=1);

require __DIR__ . '/../autoload.php';
// session_start();

if (isset($_POST['hotel-booking-submit'])) {
$bookingData = getBookingData($_POST);
}

$availability = checkAvailability($bookingData);

if ($availability > 0) {
    $_SESSION['errors'][] = 'Unfortunately, those dates are booked. Please try again. :)';
    redirect(BASE_URL . '/index.php#hotel-booking');
}

$bookingData['total-cost'] = getTotalCost($bookingData);


if (isValidUuid($bookingData['transfer-code'])) {
    $transferCodeResult = checkTransferCode($bookingData);
}   else {
    $_SESSION['errors'][] = 'Oops, your transfer code is not valid!';
    redirect(BASE_URL . '/index.php#hotel-booking');

}

if (isset($transferCodeResult['status']) && $transferCodeResult['status'] === 'success') {
    depositTransferCode($bookingData['transfer-code']);

    sendBookingData($bookingData);
    createJsonReceipt($bookingData);

}




