<?php

declare(strict_types=1);

require __DIR__ . '/app/autoload.php';

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/styles/style.css">
    <title>Hotel</title>
</head>

<body>
<?php
        if(!empty($_SESSION['message'])) {
        foreach($_SESSION['message'] as $message):
        ?>
        <p class="alert"><?=$message?></p>
        <?php endforeach; 
        }?>
    <form id="hotel-booking-form" action="app/bookings/booking.php" method="post">
        <fieldset>
            <legend>Choose your room</legend>
            <select name="rooms" id="hotel-rooms" required>
                <option value="">Please choose room</option>
                <option value="budget">Budget</option>
                <option value="standard">Standard</option>
                <option value="luxury">Luxury</option>
            </select>
        </fieldset>
        <fieldset>
            <legend>Choose your dates</legend>
            <label for="check-in-date">Check-in-date:</label><input type="date" name="check-in-date" id="check-in-date" min="2025-01-01" max="2025-01-31" required><br />
            <label for="check-out-date">Check-in-date:</label><input type="date" min="2025-01-01" max="2025-01-31" name="check-out-date" id="check-out-date" required>
        </fieldset>
        <fieldset>
            <legend>Choose your features</legend>
            <input type="checkbox" id="feature1" name="feature1" value="feature1" />
            <label for="feature1">Feature1</label><br />
            <input type="checkbox" id="feature1" name="feature1" value="feature1" />
            <label for="feature1">Feature1</label><br />
            <input type="checkbox" id="feature1" name="feature1" value="feature1" />
            <label for="feature1">Feature1</label><br />
        </fieldset>
        <fieldset>
            <legend>Enter your transfer-code:</legend>
            <input type="text" id="transfer-code" name="transfer-code" required></input>
        </fieldset>
        <fieldset id="total-cost" name="total-cost">Total cost: 

        </fieldset>
        <!-- Arrival
Departure
Room
Features
Sum -->
        <button type="submit">Book your room!</button>
    </form>

</body>

</html>