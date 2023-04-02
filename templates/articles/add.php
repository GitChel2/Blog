<?php include __DIR__ . '/../header.php'; ?>

    <form action="/articles/Add" method="POST">

        <?php if (!empty($error)): ?>
            <h3 class="error"><?= $error ?></h3>
        <?php endif; ?>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Название статьи" name="name" value="<?= $_POST['name'] ?? '' ?>">
            </div>

            <div class="form-group">
                <textarea name="text"><?= $_POST['text'] ?? '' ?></textarea><br>
            </div>

        </div>

        <div class="text-center">
            <button type="submit" class="load-more-button">Send</button>
        </div>

    </form>

<?php include __DIR__ . '/../footer.php'; ?>