
</section>
    <?php


    if (isset($_SESSION['messages']['receipt'])) {
    ?>
        <h2 id="successful-booking">Your booking is successful! Welcome to Hotel de Pierrot. We hope you enjoy your stay!</h2>
        <a href="<?= $_SESSION['messages']['receipt'] ?>" target="_blank">Your receipt</a>
        <img src="https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExbTV2cW8xOHJyZTgzdXVocTZmbXdsM3NyNXplNDVxcWd5azhvNWNzNiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/LD7LJhWI2u1lqf5oUD/giphy.gif" />
    <?php
        unset($_SESSION['messages']);
    }
    ?>
</section>
<hr>
<script src="assets/scripts/script.js"></script>
</main>
</body>
</html>