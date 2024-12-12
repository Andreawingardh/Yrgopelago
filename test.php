<?php

declare(strict_types=1);

require __DIR__ . '/app/autoload.php';

$database = new PDO('sqlite:' . __DIR__ . '/app/database/test.db');

// $statement = $database->query("SELECT * FROM bookings;");
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);

// echo "<pre>";
// $centralBanken = json_decode(file_get_contents('https://www.yrgopelago.se/centralbank/transferCode'), true);


// var_dump($centralBanken);

if (isset($_POST['submit'])) {

    /* This checks if the form has been submitted and applues variable names to check-in-date and check-out-date */
    $checkInDate = $_POST['check-in-date'];
    $checkOutDate = $_POST['check-out-date'];
    $roomID = $_POST['room_id'];
    $feature_id = $_POST['feature_id'];
    if (isset($checkInDate, $checkOutDate, $roomID)) {
        
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
        var_dump($availability);

        /* If there is availability, then the data is added to the table */
        if ($availability == 0) {

            /* This checks if the dates and room have been set and then inserts the data into the table */
            $statement = $database->prepare("INSERT INTO bookings (room_id, check_in_date, check_out_date) VALUES (:room_id, :check_in_date, :check_out_date);");

            /* This binds the parameters to the query */
            $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
            $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
            $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);

            /* This send the data to the table */
            $result = $statement->execute();

            /* This calculates the total cost of the stay */
            /* This selects the data from the table for that specific feature_id */
            $statement = $database->prepare("Select * from features WHERE id = :feature_id;");
            $statement->bindParam(':feature_id', $feature_id);
            $statement->execute();
            /* This selects the data from the table for that specific room_id */
            $features = $statement->fetch(PDO::FETCH_ASSOC);
            $statement = $database->prepare("Select * from hotel_rooms WHERE id = :room_id;");
            $statement->bindParam(':room_id', $roomID);
            $statement->execute();
            $room = $statement->fetch(PDO::FETCH_ASSOC);

            /* This converts the dates to UNIX time */
            $totalDays = (strtotime($checkOutDate) - strtotime($checkInDate))/(60*60*24);

            /* This calculates the room stay + the cost of feature*/
            $totalCost = $room['room_cost'] * $totalDays + $features['feature_cost'];

            /* This adds the total cost to $_SESSION['messages] */
            $_SESSION['messages'][] = 'Your total cost is $' . $totalCost . '.';
        

            /* This adds a successful messages to the $_SESSION['messages'] array */
            $_SESSION['messages'][] = "Successfully added dates to table";
            $_SESSION['messages'][] = 'You will check in on ' . $checkInDate . ' and out on ' . $checkOutDate;
        } else {

        /* If the dates are booked, an error message is added to the $_SESSION['messages]-array */
            $_SESSION['messages'][] = 'Sorry, these dates are booked, please try again.';
        }
    }
    var_dump($_POST);
}



    // $checkInDate = $_POST['check-in-date'];
    // $checkOutDate = $_POST['check-out-date'];
    // $roomID = $_POST['room_id'];


// function getTotalCost ($formData) {
//     $checkInDate = $formData['check-in-date'];
//     $checkOutDate = $formData['check-out-date'];
//     $features = $formData['check-box']['feature_id'];
//     $roomChoice = $formData['room_id'];
//     $totalDays = $checkOutDate - $checkInDate;
//     $roomCost = $totalDays * $roomChoice;
//     $totalCost = $roomCost + $features;
//     return $formData = ['totalCost' => $totalCost];
// }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>

<body>
    <?php
    // Adds error messages or success messages to variable $_SESSION
    if (isset($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $message):
            echo $message;
    ?><br /><?php
        endforeach;
    } ?>
    <form id="hotel-booking-form" action="" method="post">
        <fieldset>
            <legend>Choose your room</legend>
            <select name="room_id" id="room_id" required>
                <option value="">Please choose room</option>
                <option name="budget" value="1">Budget ($1)</option>
                <option name="standard" value="2">Standard ($2)</option>
                <option name="luxury" value="3">Luxury ($3)</option>
            </select>
        </fieldset>
        <fieldset name="features">
            <legend>Choose your features</legend>
            <input type="checkbox" id="1" name="feature_id" value="1" />
            <label for="feature 1">Feature1 ($2)</label><br />
            <input type="checkbox" id="2" name="feature_id" value="2" />
            <label for="feature 2">Feature2 ($1)</label><br />
            <input type="checkbox" id="3" name="feature_id" value="3" />
            <label for="feature 3">Feature3 ($2)</label><br />
        </fieldset>
        <fieldset>
            <legend>Choose your dates</legend>
            <label for="check-in-date">Check-in-date:</label><input type="date" name="check-in-date" id="check-in-date" min="2025-01-01" max="2025-01-31" required><br />
            <label for="check-out-date">Check-in-date:</label><input type="date" min="2025-01-01" max="2025-01-31" name="check-out-date" id="check-out-date" required>
        </fieldset>
        <button type="submit" name="submit">Book your room!</button>
    </form>
</body>

</html>