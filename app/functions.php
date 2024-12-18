<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';


use GuzzleHttp\Exception\ClientException;


/* This function creates an array of the booking data */

function getBookingData($formData)
{

    $database = new PDO('sqlite:' . __DIR__ . '/database/test.db');
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

/* This function is a draft and should not be used */
// function checkTransferCode2($formData)
// {

//     /* Fetching transfercode from centralbanken-API */
//     $transferCode = trim(htmlspecialchars($formData['transfer-code']));
//     $totalCost = $formData['total-cost'];

//     /*Checking if the transfercode is set */
//     if (isset($transferCode) && !empty($transferCode)) {

//         /* Fetching the transfercode endpoint from the centralbanken API and turning it into an array*/

//         $centralBanken = json_decode(file_get_contents('https://www.yrgopelago.se/centralbank/transferCode'), true);

//         /* Giving the centralbanken transfer code a variable name */
//         $centralBankenTransferCode = $centralBanken['response (success 200 OK)']['transferCode'];

//         /* Giving the centralbanken transfer code amount a variable name */
//         $centralBankenTransferCodeAmount = $centralBanken['response (success 200 OK)']['amount'];

//         /* Checking if the transfer code exists in API and checking the amount */
//         if ($transferCode === $centralBankenTransferCode && $centralBankenTransferCodeAmount >= $totalCost) {

//             /* Adding the transfercode and username to deposit endpoint in Centralbanken */
//             $deposit = json_decode(file_get_contents('https://www.yrgopelago.se/centralbank/deposit'), true);

//             /* Add the transfer Code and Username to deposit array */
//             $depost['form_params'] = ['user' => 'Andy', 'transferCode' => $transferCode];
//             file_put_contents('https://www.yrgopelago.se/centralbank/deposit', (json_encode($deposit)));
//         } else {
//             redirect('/');
//             $_SESSION['message'] = ['Transfercode is not set'];
//         }
//     } else {
//         redirect('/');
//         $_SESSION['message'] = ['Transfercode is not valid'];
//     }
// }



/*This function checks availability of hotel rooms */

function checkAvailability($bookingData)
{
    $database = new PDO('sqlite:' . __DIR__ . '/database/test.db');

    $checkInDate = $bookingData['check-in-date'];
    $checkOutDate = $bookingData['check-out-date'];
    $roomID = $bookingData['room-id'];

    /* This checks if there is availability in the room at the given dates */
    $statement = $database->prepare("SELECT COUNT(*) as availability FROM bookings
        WHERE room_id = :room_id
        AND (check_in_date < :check_out_date AND check_out_date > :check_in_date);");

    /* Binds the relevant variables to the parameters */
    $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
    $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
    $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);
    $statement->execute();

    /* $availability returns 0 if there is an availability, a higher number if the room is booked */
    $availability = $statement->fetchColumn();
    return $availability;
}

/* This function gets total cost of booking */
function getTotalCost($bookingData)
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
    echo 'Feature cost: ' . $totalFeatureCost;

    /* This converts the dates to UNIX time */
    $totalDays = (strtotime($checkOutDate) - strtotime($checkInDate)) / (60 * 60 * 24);

    /* This calculates the room stay + the cost of feature*/
    $totalCost = ($roomCost * $totalDays) + $totalFeatureCost;
    $bookingData['total-cost'] = $totalCost;

    /* This adds the total cost to $_SESSION['messages] */
    $_SESSION['messages'][] = 'Your total cost is $' . $totalCost . '.';

    return $bookingData['total-cost'];
}

/* This functions checks the validity of the transfercode */

function checkTransferCode($bookingData)
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
        var_dump($transferCodeResult);
        return $transferCodeResult;
    } catch (ClientException $e) {
        /* If the fetch returns an error response, this code gets the contents of the response and adds it to $_SESSION['messages'] */
        $response = $e->getResponse();
        $errorContent = $response->getBody()->getContents();

        $errorMessage = json_decode($errorContent, true);
        return $_SESSION['messages'][] = $errorMessage['error'];
    }
}


/* This function deposits the transfer code */
function depositTransferCode($transferCode)
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
        return $_SESSION['messages'][] = $errorMessage['error'];
    }
}



/* This function sends the booking data to the database */
/*@TODO: Add the value for tourist or name to the insert query. */
/*@TODO: Figure out why the insert into booking_feature table is inserting the wrong data */
function sendBookingData($bookingData)
{

    $database = $bookingData['database'];
    $roomID = $bookingData['room-id'];
    $checkInDate = $bookingData['check-in-date'];
    $checkOutDate = $bookingData['check-out-date'];
    $features = $bookingData['feature_id'] ?? [];

    /* This checks if the dates and room have been set and then inserts the data into the table */
    $statement = $database->prepare("INSERT INTO bookings (room_id, check_in_date, check_out_date) VALUES (:room_id, :check_in_date, :check_out_date);");

    /* This binds the parameters to the query */
    $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
    $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
    $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);

    /* This send the data to the table */
    $statement->execute();

    /* This gets the last inserted ID for putting features into booking_feature table */
    $bookingID = $database->lastInsertId();

    /* This inserts features into an array with the corresponding booking_id */
    // if (!empty($features)) {
    //     $insertFeatures = [];
    //     foreach ($features as $feature) {
    //         $insertFeatures[] = [
    //             'booking_id' => $bookingID,
    //             'feature_id' => $feature
    //         ];
    //     }
    // }

    /* This prepares a statement to insert the chosen features into booking_feature table */
    $statement = $database->prepare("INSERT INTO booking_feature (booking_id, feature_id) VALUES (:booking_id, :feature_id);");

    /* This loops through the chosen features and adds them to booking_feature table */
    foreach ($features as $feature) {
        $statement->bindParam(':booking_id', $bookingID);
        $statement->bindParam(':feature_id', $feature);
        $statement->execute();
    }



    /* This adds a successful messages to the $_SESSION['messages'] array */
    $_SESSION['messages'][] = "Successfully added dates to table";
    $_SESSION['messages'][] = 'You will check in on ' . $checkInDate . ' and out on ' . $checkOutDate;
}

/* This function writes the data to a json-file and sends the user a link */
/*@TODO: Why does the file_put_contents part of this function not work */
function createJsonReceipt($bookingData)
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
            'island' => "DEFAULT ISLAND",
            'hotel' => "DEFAULT HOTEL",
            "arrival_date" => $bookingData['check-in-date'],
            "departure_date" => $bookingData['check-out-date'],
            "total_cost" => $bookingData['total-cost'],
            "stars" => "3",
            "features" => $featuresInformation
        ],
        "additional_info" => [
            "greeting" => "Thank you for choosing DEFAULT HOTEL",
            "imageUrl" => "https://upload.wikimedia.org/wikipedia/commons/e/e2/Hotel_Boscolo_Exedra_Nice.jpg"
        ]
    ];

    file_put_contents('receipt.json', json_encode($receipt));

    $_SESSION['messages'][] = '
    <a href="/receipt.json" target="_blank">Your receipt</a>
    <?php ';
    return header('Location: /');

    // $jsonResult = json_encode($receipt, JSON_PRETTY_PRINT);

    // if ($jsonResult === false) {
    //     // Log JSON encoding error
    //     $_SESSION['messages'][] = 'Error encoding JSON: ' . json_last_error_msg();
    //     return false;
    // }

    // $writeResult = file_put_contents('receipt.json', $jsonResult);

    // if ($writeResult === false) {
    //     $_SESSION['messages'][] = 'Error writing JSON file';
    //     return false;
    // }

    // $_SESSION['messages'][] = '<a href="/receipt.json" target="_blank">Your receipt</a>';
    // return header('Location: /');
}
