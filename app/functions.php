<?php
declare(strict_types=1);



function redirect(string $path)
{
    header("Location: $path");
    exit;
}

function checkTransferCode($formData) {

/* Fetching transfercode from centralbanken-API */
    $transferCode = $formData['transfer-code'];
    if(isset($transferCode)) {
    $centralBanken = file_get_contents('https://www.yrgopelago.se/centralbank/transferCode');
    $centralBankenTransferCode = $centralBanken['response']['transferCode'];
    $centralBankenTransferCodeAmount = $centralBanken['response']['amount'];
    if ($transferCode === $centralBankenTransferCode && $centralBankenTransferCodeAmount >= $formData['total-cost'] >= $centralBankenTransferCodeAmount) {
        
    }



    }

}

function getBooking ($formData) {
}

/* This function gets the total cost of the stay to send to the API */
function getTotalCost ($formData) {
    $checkInDate = $formData['check-in-date'];
    $checkOutDate = $formData['check-out-date'];
    $features = $formData['features'];
    $roomChoice = $formData['hotel-rooms'];
    $totalDays = $checkOutDate - $checkInDate;
    $roomCost = $totalDays * $roomChoice;
    $totalCost = $roomCost + $features;
    return $formData = ['totalCost' => $totalCost];
}