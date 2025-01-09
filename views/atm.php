
<section id="atm">
    <h2>Get your transfercode here!</h2>
    <p>Here at Hotel de Pierrot we make it easy for you to get the money you need to book a room. The money is only a click away!</p>
    <p></p>
    <form id="withdraw-form" action="<?php BASE_URL?>/index.php#atm" method="post">
        <fieldset>
            <legend>Name:</legend>
            <input type="text" name="user" label="user"></input>
        </fieldset>
        <fieldset>
            <legend>Enter your API-key:</legend>
            <input type="text" id="api_key" name="api_key" required></input>
        </fieldset>
        <fieldset>
            <legend>Amount:</legend>
            <input type="text" id="amount" name="amount" required></input>
        </fieldset>
        <button type="submit" name="atm-submit">Get transfercode!</button>
    </form>
    <?php
    if (isset($_POST['atm-submit'])) {
        $withdrawResult = withdrawTransferCode($_POST);
        if (isset($withdrawResult['transferCode'])) {
    ?>
            <p class="withdrawal">Your transfercode: <?= $withdrawResult['transferCode']; ?><br>
                Your amount: <?= $withdrawResult['amount']; ?></p>
                <p>Your transfercode and name have been entered into the booking form!</p>
    <?php
            // unset($withdrawResult);
            // $_POST = [];
        } else if (!isset($withdrawResult['transferCode'])) {
            foreach ($_SESSION['messages'] as $error) {
                echo 'Error: ' . $error . ' Please try again.';
            }
        }
    }
    ?>
</section>
<hr>