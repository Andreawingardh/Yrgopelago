<?php

declare(strict_types=1);

?>





    <section id="calendar">
        <h2>Calendar</h2>
        <p>Check out out when our rooms are free!</p>
        <div class="display-buttons"><button id="budget-calendar-button">Budget Single</button> <button id="standard-calendar-button">Superior Double</button><button id="luxury-calendar-button">Circus Suite</button></div>
        <div id="budget-room">
            <h2>Budget Single</h2><?php getCalendar(1); ?>
        </div>
        <div id="standard-room">
            <h2>Superior Double</h2> <?php getCalendar(2); ?>
        </div>
        <div id="luxury-room">
            <h2>Circus Suite</h2> <?php getCalendar(3); ?>
        </div>
        <script src="/assets/scripts/script.js"></script>
    </section>
