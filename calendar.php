<?php

declare(strict_types=1);

?>

<section id="calendar">
    <h1>Check out when our rooms are booked</h1>
    <button id="budget-button">Budget</button> <button id="standard-button">Standard</button><button id="luxury-button">Luxury</button>
    <div id="budget-room"><?php getCalendar("1"); ?></div>
    <div id="standard-room"><?php getCalendar("2"); ?></div>
    <div id="luxury-room"><?php getCalendar("3"); ?></div>
    <script src="/assets/scripts/script.js"></script>
</section>