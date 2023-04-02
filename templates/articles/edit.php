<?php
/**
 * @var \MyProject\Models\Articles\Article $article
 */
include __DIR__ . '/../header.php';
?>

    <form action="/articles/edit/<?= $article->getId() ?>" method="POST">

        <?php if (!empty($error)): ?>
            <h3 class="error"><?= $error ?></h3>
        <?php endif; ?>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Название статьи" name="name" value="<?= $_POST['name'] ?? $article->getName() ?>">
            </div>

            <div class="form-group">
                <textarea name="text"><?= $_POST['text'] ?? $article->getText() ?></textarea><br>
            </div>

            <a href="/articles/Id/<?= $article->getId() ?>">Отменить</a>

        </div>

        <div class="text-center">
            <button type="submit" class="load-more-button">Send</button>
        </div>

    </form>

<?php include __DIR__ . '/../footer.php'; ?>