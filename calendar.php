<?php

declare(strict_types=1);

?>



<section id="pre-booking">

<section id="calendar">
    <h2>Ready to book?</h2>
    <p>Check out out when our rooms are free!</p>
    <div class="display-buttons"><button id="budget-button">Budget</button> <button id="standard-button">Standard</button><button id="luxury-button">Luxury</button></div>
    <div id="budget-room"><h2>Budget</h2><?php getCalendar(1); ?></div>
    <div id="standard-room"><h2>Standard</h2> <?php getCalendar(2); ?></div>
    <div id="luxury-room"><h2>Luxury</h2> <?php getCalendar(3); ?></div>
    <script src="/assets/scripts/script.js"></script>
</section>
</section>