<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;
use GuzzleHttp\Exception\ClientException;




/* This function allows the user to create a transfer code */

function withdrawTransferCode(array $formData): array
{

    $user = trim(htmlspecialchars($formData['user']));
    $apiKey = trim(htmlspecialchars($formData['api_key']));
    $amount = trim(htmlspecialchars($formData['amount']));

    try {
        $client = new GuzzleHttp\Client();
        /* This send the data from the form to the api-endpoint */
        $res = $client->request('POST', 'https://yrgopelago.se/centralbank/withdraw', [
            'form_params' => [
                'user' => $user,
                'api_key' => $apiKey,
                'amount' => $amount
            ]
        ]);

        $body = $res->getBody();
        $stringBody = (string) $body;
        $withdrawResult = json_decode($stringBody, true);
        return $withdrawResult;
    } catch (ClientException $e) {
        /* If the fetch returns an error response, this code gets the contents of the response and adds it to $_SESSION['messages'] */
        $response = $e->getResponse();
        $errorContent = $response->getBody()->getContents();

        $errorMessage = json_decode($errorContent, true);
        $_SESSION['messages']['error'] = $errorMessage['error'];
        return $_SESSION['messages'];
    }
}

/* This function creates an array of the booking data */

function getBookingData(array $formData): array
{

    $database = new PDO('sqlite:' . __DIR__ . '/database/database.db');
    $tourist = trim(htmlspecialchars($formData['tourist']));
    $checkInDate = trim(htmlspecialchars($formData['check-in-date']));
    $checkOutDate = trim(htmlspecialchars($formData['check-out-date']));
    $roomID = htmlspecialchars($formData['room_id']);
    $transferCode = trim(htmlspecialchars($formData['transferCode']));
    $features = $formData['feature_id'] ?? [];
    $totalCost = '';
    $bookingID = '';

    $bookingData = [
        'database' => $database,
        'tourist' => $tourist,
        'booking-id' => $bookingID,
        'check-in-date' => $checkInDate,
        'check-out-date' => $checkOutDate,
        'room-id' => $roomID,
        'features' => $features,
        'transfer-code' => $transferCode,
        'total-cost' => $totalCost
    ];

    return $bookingData;
}

/* Redirect function */

function redirect(string $path)
{
    header("Location: $path");
    exit;
}

/* Tests validity of transfer code */
function isValidUuid(string $uuid): bool
{

    if (!is_string($uuid) || (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) !== 1)) {
        return false;
    }

    return true;
}


/*This function checks availability of hotel rooms */

function checkAvailability(array $bookingData): int | string
{
    $database = $bookingData['database'];
    $checkInDate = $bookingData['check-in-date'];
    $checkOutDate = $bookingData['check-out-date'];
    $roomID = $bookingData['room-id'];


    if ($checkOutDate >= $checkInDate) {
        /* This checks if there is availability in the room at the given dates */
        $statement = $database->prepare("SELECT COUNT(*) as availability FROM bookings
        WHERE room_id = :room_id
        AND (check_in_date <= :check_out_date AND check_out_date >= :check_in_date);");

        /* Binds the relevant variables to the parameters */
        $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
        $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
        $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);
        $statement->execute();

        /* $availability returns 0 if there is an availability, a higher number if the room is booked */
        $availability = $statement->fetchColumn();
        return $availability;
    } else {
        $_SESSION['errors'][] = "Your dates are reversed";
        return redirect(BASE_URL . '/index.php#hotel-booking');
    }
}

/* This function gets total cost of booking */
function getTotalCost(array $bookingData): int
{
    $database = $bookingData['database'];
    $roomID = $bookingData['room-id'];
    $checkInDate = $bookingData['check-in-date'];
    $checkOutDate = $bookingData['check-out-date'];
    $features = $bookingData['features'];

    /* This gets the cost of the room for that specific hotel room */
    $statement = $database->prepare("Select room_cost from hotel_rooms WHERE id = :room_id;");
    $statement->bindParam(':room_id', $roomID);
    $statement->execute();
    $roomCost = $statement->fetchColumn();

    /*This checks the cost of the chosen features*/
    $totalFeatureCost = 0;
    $statement = $database->prepare("SELECT feature_cost
        from features
        where id = :feature_id;");
    foreach ($features as $feature) {
        $statement->bindParam(':feature_id', $feature);
        $statement->execute();
        $singleFeatureCost = $statement->fetchColumn();
        $totalFeatureCost += $singleFeatureCost;
    }

    /* This converts the dates to UNIX time */
    $totalDays = ((strtotime($checkOutDate) - strtotime($checkInDate)) / (60 * 60 * 24)) + 1;

    /* This calculates the room stay + the cost of feature*/
    $totalCost = ($roomCost * $totalDays) + $totalFeatureCost;
    $bookingData['total-cost'] = $totalCost;
    '.';

    return $bookingData['total-cost'];
}

/* This functions checks the validity of the transfercode */

function checkTransferCode(array $bookingData): array | string
{
    $transferCode = $bookingData['transfer-code'];
    $totalCost = $bookingData['total-cost'];

    try {
        $client = new GuzzleHttp\Client();
        /* This checks if the transfercode and total cost match*/
        $res = $client->request('POST', 'https://yrgopelago.se/centralbank/transferCode', [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost
            ]
        ]);
        /* This converts the data from the API endpoint to be accessed as an array */
        $body = $res->getBody();
        $stringBody = (string) $body;
        $transferCodeResult = json_decode($stringBody, true);
        return $transferCodeResult;
    } catch (ClientException $e) {
        /* If the fetch returns an error response, this code gets the contents of the response and adds it to $_SESSION['messages'] */
        $response = $e->getResponse();
        $errorContent = $response->getBody()->getContents();

        $errorMessage = json_decode($errorContent, true);
        $_SESSION['errors'][] = $errorMessage['error'];
        return redirect(BASE_URL . '/index.php#hotel-booking');
    }
}


/* This function deposits the transfer code */
function depositTransferCode(string $transferCode): string
{
    try {

        /* This sends the data to the deposit endpoint */
        $client = new GuzzleHttp\Client();
        $res = $client->request('POST', 'https://yrgopelago.se/centralbank/deposit', [
            'form_params' => [
                'user' => 'Andy',
                'transferCode' => $transferCode
            ]
        ]);
        return $_SESSION['messages'][] = "Your booking is successful";
    } catch (ClientException $e) {
        /* If the fetch returns an error response, this code gets the contents of the response and adds it to $_SESSION['messages'] */
        $response = $e->getResponse();
        $errorContent = $response->getBody()->getContents();

        $errorMessage = json_decode($errorContent, true);
        $_SESSION['errors'][] = $errorMessage['error'];
        return redirect(BASE_URL . '/index.php#hotel-booking');
    }
}



/* This function sends the booking data to the database */
function sendBookingData(array $bookingData)
{

    $database = $bookingData['database'];
    $tourist = $bookingData['tourist'];
    $roomID = $bookingData['room-id'];
    $checkInDate = $bookingData['check-in-date'];
    $checkOutDate = $bookingData['check-out-date'];
    $features = $bookingData['features'] ?? [];
    $transferCode = $bookingData['transfer-code'];
    $totalCost = $bookingData['total-cost'];

    /* This checks if the dates and room have been set and then inserts the data into the table */
    $statement = $database->prepare("INSERT INTO bookings (tourist, room_id, check_in_date, check_out_date, transfer_code, total_cost) VALUES (:tourist, :room_id, :check_in_date, :check_out_date, :transfer_code, :total_cost);");

    /* This binds the parameters to the query */
    $statement->bindParam(':tourist', $tourist, PDO::PARAM_STR);
    $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
    $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
    $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);
    $statement->bindParam(':transfer_code', $transferCode, PDO::PARAM_STR);
    $statement->bindParam(':total_cost', $totalCost, PDO::PARAM_INT);

    /* This send the data to the table */
    $statement->execute();


    /* This selects the latest ID entered */
    $statement = $database->prepare("SELECT id
    FROM bookings
    where transfer_code = :transfer_code;");
    $statement->bindParam(':transfer_code', $transferCode);
    $statement->execute();
    $bookingId = $statement->fetchColumn();

    /* This prepares a statement to insert the chosen features into booking_feature table */
    $statement = $database->prepare("INSERT INTO booking_feature (booking_id, feature_id) VALUES (:booking_id, :feature_id);");

    /* This loops through the chosen features and adds them to booking_feature table */
    if (!empty($features)) {
        foreach ($features as $feature) {
            $statement->bindParam(':booking_id', $bookingId, PDO::PARAM_INT);
            $statement->bindParam(':feature_id', $feature, PDO::PARAM_INT);
            $statement->execute();
        }
    }
}

/* This function writes the data to a json-file and sends the user a link */
function createJsonReceipt(array $bookingData)
{

    $database = $bookingData['database'];
    $features = $bookingData['features'] ?? [];
    $featuresInformation = [];


    $statement = $database->prepare("SELECT feature_item, feature_cost
        from features
        where id = :feature_id;");
    if (!empty($features)) {
        foreach ($features as $feature) {
            $statement->bindParam(':feature_id', $feature);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $featuresInformation[] = [
                'feature' => $result['feature_item'],
                'cost' => $result['feature_cost']
            ];
        }
    }

    $receipt = [
        [
            'island' => "Clown Island",
            'hotel' => "Hotel de Pierrot",
            "arrival_date" => $bookingData['check-in-date'],
            "departure_date" => $bookingData['check-out-date'],
            "total_cost" => $bookingData['total-cost'],
            "stars" => "3",
            "features" => $featuresInformation
        ],
        "additional_info" => [
            "greeting" => "Thank you for choosing Hotel de Pierrot",
            "imageUrl" => "https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExbTV2cW8xOHJyZTgzdXVocTZmbXdsM3NyNXplNDVxcWd5azhvNWNzNiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/LD7LJhWI2u1lqf5oUD/giphy.gif"
        ]
    ];

    file_put_contents('receipt.json', json_encode($receipt));

    $_SESSION['messages']['receipt'] = BASE_URL . '/app/bookings/receipt.json';
    $_SESSION['messages']['image'] = 'https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExbTV2cW8xOHJyZTgzdXVocTZmbXdsM3NyNXplNDVxcWd5azhvNWNzNiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/LD7LJhWI2u1lqf5oUD/giphy.gif';
    return redirect(BASE_URL . '/index.php#successful-booking');
}

/* Function to show calendar */
function getCalendar(int $roomID)
{
    $database = new PDO('sqlite:' . __DIR__ . '/database/database.db');
    $calendar = new Calendar;
    $events = [];
    $statement = $database->prepare("SELECT check_in_date, check_out_date
FROM bookings
where room_id = :room_id;");
    $statement->bindParam(':room_id', $roomID);
    $statement->execute();
    $bookedDates = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($bookedDates as $date) {
        $events[] = [
            'start' => $date['check_in_date'],
            'end' => $date['check_out_date'],
            'mask' => true,
            'classes' => ['myclass', 'abc']
        ];
    }

    $calendar->addEvents($events);
    $calendar->useMondayStartingDate();

    echo $calendar->draw(date('2025-01-01'));
}
