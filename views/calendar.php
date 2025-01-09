
    <section id="calendar">
        <h2>Calendar</h2>
        <p>Check out out when our rooms are free!</p>
        <div class="display-buttons"><button id="budget-calendar-button">Budget Single</button> <button id="standard-calendar-button">Superior Double</button><button id="luxury-calendar-button">Circus Suite</button></div>
        <div id="budget-calendar">
            <h2>Budget Single</h2><?php getCalendar(1); ?>
        </div>
        <div id="standard-calendar">
            <h2>Superior Double</h2> <?php getCalendar(2); ?>
        </div>
        <div id="luxury-calendar">
            <h2>Circus Suite</h2> <?php getCalendar(3); ?>
        </div>
    </section>