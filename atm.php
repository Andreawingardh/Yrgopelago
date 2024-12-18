<?php

declare(strict_types=1);


if (isset($_POST['submit'])) {
    $withdrawResult = withdrawTransferCode($_POST);
    echo "Your transfercode: " . $withdrawResult['transferCode'];
    echo "Your amount: " . $withdrawResult['amount'];
}


?>
<h1>Want to make a withdrawal?</h1>
<form id="withdraw-form" action="atm.php" method="post">
        <fieldset>
            <legend>Name:</legend>
            <textarea name="user" label="user"></textarea>
        </fieldset>
        <fieldset>
            <legend>Enter your API-code:</legend>
            <input type="text" id="api-key" name="api-key" required></input>
        </fieldset>
        <fieldset>
            <legend>Amount:</legend>
            <input type="text" id="amount" name="amount" required></input>
        </fieldset>
        <button type="submit" name="submit">Get transfercode!</button>
    </form>


</body>
</html>