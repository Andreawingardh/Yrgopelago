<?php

declare(strict_types=1);

require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Exception\ClientException;


    $S_SESSION['messages'] = [];

if (isset($_POST['submit'])) {
    $transferCode = $_POST['transferCode'];
    $totalCost = $_POST['totalCost'];


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


    if(isset($transferCodeResult['status']) && $transferCodeResult['status'] = 'success') {
        /* This sends the data to the deposit endpoint */
        $res = $client->request('POST', 'https://yrgopelago.se/centralbank/deposit', [
            'form_params' => [
                'user' => 'Andy',
                'transferCode' => $transferCode
            ]
        ]);
        $_SESSION['messages'][] = "Your booking is successful";
    }
}

catch (ClientException $e) {
/* If the fetch returns an error response, this code gets the contents of the response and adds it to $_SESSION['messages'] */
    $response = $e->getResponse();
    $errorContent = $response->getBody()->getContents();

    $errorMessage = json_decode($errorContent, true);
    $_SESSION['messages'][] = $errorMessage['error'];
}
}


if (isset($_SESSION['messages'])) { 

var_dump($_SESSION['messages']);

}
?>


<form id="hotel-booking-form" action="" method="post">
    <fieldset>
        <legend>Enter your transfer-code:</legend>
        <input type="text" id="transferCode" name="transferCode" required></input>
    </fieldset>
    <fieldset>
        <legend>Enter total cost</legend>
        <input type="text" id="totalCost" name="totalCost"></input>
    </fieldset>
    <button type="submit" name="submit">Book your room!</button>
</form>



<?php var_dump($_POST);
