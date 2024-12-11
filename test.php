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
    if (isset($checkInDate, $checkOutDate, $roomID)) {
    /* This checks if the dates and room have been set and then inserts the data into the table */
        $statement = $database->prepare("INSERT INTO bookings (room_id, check_in_date, check_out_date) VALUES (:room_id, :check_in_date, :check_out_date);");
        $statement->bindParam(':room_id', $roomID, PDO::PARAM_INT);
        $statement->bindParam(':check_in_date', $checkInDate, PDO::PARAM_STR);
        $statement->bindParam(':check_out_date', $checkOutDate, PDO::PARAM_STR);
        $result = $statement->execute();
        $_SESSION['messages'][] = "Successfully added dates to table";
    }
    $_SESSION['messages'][] = 'You will check in on ' . $checkInDate . ' and out on ' . $checkOutDate;
    var_dump($_POST);
}


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
    foreach($_SESSION['messages'] as $message):
    echo $message;
    ?><br /><?php
    endforeach;
} ?>
    <form id="hotel-booking-form" action="" method="post">
    <fieldset>
            <legend>Choose your room</legend>
            <select name="room_id" id="room_id" required>
                <option value="">Please choose room</option>
                <option name="budget" value="1">Budget</option>
                <option name= "standard" value="2">Standard</option>
                <option name= "luxury" value="3">Luxury</option>
            </select>
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