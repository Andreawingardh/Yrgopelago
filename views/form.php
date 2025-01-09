<section id=hotel-booking>
    <section class="booking-information">
        <form id="hotel-booking-form" action="app/bookings/booking.php" method="post">
            <h2>Book a room!</h2>
            <p>Ready to get clowning? Book a room with us today!</p>
            <?php
            if (!empty($_SESSION['errors'])) {
            ?><div class="error">
                    <?php
                    foreach ($_SESSION['errors'] as $error) {
                        echo $error;
                    }
                    unset($_SESSION['errors']);
                    ?>
                </div>
            <?php
            }
            ?>
            <fieldset>
                <legend>What's your name?</legend>
                <input type="text" name="tourist" label="name"
                    <?php
                    if (isset($_SESSION['messages']['user'])) {
                    ?>
                    value="<?= $_SESSION['messages']['user']; ?>"
                    <?php } ?>></input>
            </fieldset>
            <fieldset>
                <legend>Choose your room</legend>
                <select name="room_id" id="hotel-rooms" required>
                    <option value="">Please choose room</option>
                    <option value="1">No Money In The Bank, Gotta Get to Clowning Budget Single ($1)</option>
                    <option value="2">Clown All Day Sleep All Night Superior Double ($2)</option>
                    <option value="3">Ultra Deluxe Manic Circus Suite ($3)</option>
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
                    <label for="Ping pong table">Ping pong table ($2)</label>
                </div>
                <div class="checkbox"><input type="checkbox" class="feature" name="feature_id[]" value="2" />
                    <label for="Yatzy">Yatzy ($1)</label>
                </div>
                <div class="checkbox"><input type="checkbox" class="feature" name="feature_id[]" value="3" />
                    <label for="Unicycle">Unicycle ($2)</label>
                </div>
            </fieldset>
            <fieldset id="transfer-code-fieldset">
                <legend>Enter your transfer-code:</legend>
                <input type="text" id="transfer-code" name="transferCode"
                    <?php
                    if (isset($_SESSION['messages']['transferCode'])) {
                    ?>
                    value="<?= $_SESSION['messages']['transferCode'];
                    ?>"
                    <?php } ?>
                    required>
                <?php
                if (isset($_SESSION['messages']['amount'])) {
                    echo "$(" . $_SESSION['messages']['amount'] . ")";
                    session_unset();
                }
                ?>
                </input>

            </fieldset>
            <fieldset id="total-cost" name="total-cost">Total cost:
            </fieldset>
            <button type="submit" name="hotel-booking-submit">Book your room!</button>
        </form>