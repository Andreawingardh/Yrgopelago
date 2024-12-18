<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

function getCalendar($roomID)
{
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/test.db');
    $calendar = new Calendar;
    $events = [];
    $statement = $database->prepare("SELECT check_in_date, check_out_date
FROM bookings
where room_id = :room_id;");
    $statement->bindParam(':room_id', $roomID);
    $statement->execute();
    $bookedDates = $statement->fetchAll(PDO::FETCH_ASSOC);
    var_dump($bookedDates);

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/assets/styles/calendar.css">
    <title>Document</title>
</head>

<body>
    <button id="budget-button">Budget</button> <button id="standard-button">Standard</button><button id="luxury-button">Luxury</button>
    <div id="budget-room"><?php getCalendar("1"); ?></div>
    <div id="standard-room"><?php getCalendar("2"); ?></div>
    <div id="luxury-room"><?php getCalendar("3"); ?></div>
    <script>
        const budgetRoom = document.getElementById("budget-room");
        const standardRoom = document.getElementById("standard-room");
        const luxuryRoom = document.getElementById("luxury-room");

        // Initially hide all rooms
        budgetRoom.style.display = 'none';
        standardRoom.style.display = 'none';
        luxuryRoom.style.display = 'none';

        const budgetButton = document.getElementById("budget-button");
        const standardButton = document.getElementById("standard-button");
        const luxuryButton = document.getElementById("luxury-button");

        // Function to hide all rooms
        function hideAllRooms() {
            budgetRoom.style.display = 'none';
            standardRoom.style.display = 'none';
            luxuryRoom.style.display = 'none';
        }

        // Add click event listeners to buttons
        budgetButton.addEventListener('click', () => {
            hideAllRooms();
            budgetRoom.style.display = 'block';
        });

        standardButton.addEventListener('click', () => {
            hideAllRooms();
            standardRoom.style.display = 'block';
        });

        luxuryButton.addEventListener('click', () => {
            hideAllRooms();
            luxuryRoom.style.display = 'block';
        });
    </script>
</body>

</html>