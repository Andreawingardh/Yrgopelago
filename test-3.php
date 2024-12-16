<?php

declare(strict_types=1);

$database = new PDO('sqlite:' . __DIR__ . '/app/database/test.db');

if (isset($_POST['submit'])) {

    //lastInsertId();

    $features = $_POST['feature_id'] ?? [];
    $bookingID = $_POST['booking_id'];

    $insertFeatures = [];
    foreach ($features as $feature) {
        $insertFeatures[] = [
            'booking_id' => $bookingID,
            'feature_id' => $feature
        ];
    }

    $statement = $database->prepare("INSERT INTO booking_feature (booking_id, feature_id) VALUES (:booking_id, :feature_id);");

    foreach($insertFeatures as $feature) {
    $statement->bindParam(':booking_id', $feature['booking_id']);
    $statement->bindParam(':feature_id', $feature['feature_id']);
    $statement->execute();
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/test-3.php" method="post">
        <fieldset name="features">
            <textarea name="booking_id"></textarea>
            <legend>Choose your features</legend>
            <input type="checkbox" id="1" name="feature_id[]" value="1" />
            <label for="feature 1">Feature1 ($2)</label><br />
            <input type="checkbox" id="2" name="feature_id[]" value="2" />
            <label for="feature 2">Feature2 ($1)</label><br />
            <input type="checkbox" id="3" name="feature_id[]" value="3" />
            <label for="feature 3">Feature3 ($2)</label><br />
        </fieldset>
        <button type="submit" name="submit">Book your room!</button>
    </form>
</body>

</html>