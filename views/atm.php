<?php

declare(strict_types=1);





?>
<section id="atm">
    <h2>Want to make a withdrawal?</h2>
    <p>Here at Hotel de Pierrot we make it easy for you to get the money you need to book a room. The money is only a click away!</p>
    <form id="withdraw-form" action="/#atm" method="post">
        <fieldset>
            <legend>Name:</legend>
            <input type="text" name="user" label="user"></input>
        </fieldset>
        <fieldset>
            <legend>Enter your API-code:</legend>
            <input type="text" id="api-key" name="api-key" required></input>
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
    <?php } else if (!isset($withdrawResult['transferCode'])) {
            foreach ($_SESSION['messages'] as $error) {
                echo 'Error: ' . $error . ' Please try again.';
            }
            unset($_POST['atm-submit']);
        }
    }
    ?>
</section>