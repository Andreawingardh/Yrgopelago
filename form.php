<?php

declare(strict_types=1);





?>
<section id=hotel-booking>
    <h2>Book a room!</h2>
    <p>Ready to get clowning? Book a room with us today!</p>

    <form id="hotel-booking-form" action="app/bookings/booking.php" method="post">
        <fieldset>
            <legend>What's your name?</legend>
            <input type="text" name="tourist" label="name"></input>
        </fieldset>
        <fieldset>
            <legend>Choose your room</legend>
            <select name="room_id" id="hotel-rooms" required>
                <option value="">Please choose room</option>
                <option value="1">Budget</option>
                <option value="2">Standard</option>
                <option value="3">Luxury</option>
            </select>
        </fieldset>
        <fieldset>
            <legend>Choose your dates</legend>
            <label for="check-in-date">Check-in-date:</label><input type="date" name="check-in-date" id="check-in-date" min="2025-01-01" max="2025-01-31" required><br />
            <label for="check-out-date">Check-in-date:</label><input type="date" min="2025-01-01" max="2025-01-31" name="check-out-date" id="check-out-date" required>
        </fieldset>
        <fieldset>
            <legend>Choose your features</legend>
            <div class="checkbox"><input type="checkbox" class="feature" name="feature_id[]" value="1" />
                <label for="feature1">Ping pong table ($2)</label>
            </div>
            <div class="checkbox"><input type="checkbox" class="feature" name="feature_id[]" value="2" />
                <label for="feature2">Yatzy ($1)</label>
            </div>
            <div class="checkbox"><input type="checkbox" class="feature" name="feature_id[]" value="3" />
            <label for="feature3">Unicycle ($2)</label></div>
        </fieldset>
        <fieldset>
            <legend>Enter your transfer-code:</legend>
            <input type="text" id="transfer-code" name="transferCode" required></input>
        </fieldset>
        <fieldset id="total-cost" name="total-cost">Total cost:

        </fieldset>
        <button type="submit">Book your room!</button>
    </form>
    <?php
    if (isset($messages)) {
        foreach ($messages as $message) {
            echo $message;
            echo "<br>";
        }
    }

    ?>
</section>