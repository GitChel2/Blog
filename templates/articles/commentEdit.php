<?php
include __DIR__ . '/../header.php';

/**
 * @var \Project\Models\Users\User $user
 */

/**
 * @var \MyProject\Models\Articles\Article $article
 */

/**
 * @var \Project\Models\Articles\Comments $comment
 */

?>

    <!-- Статья -->
    <div class="blog-post">

        <div class="post-title">
            <h1><?= $article->getName() ?></h1>
        </div>

        <div class="post-info">
            <span><?= $article->getCreatedAt() ?> / by <a href="/users/Profile/Id/<?= $article->getAuthor()->getId() ?>" target="_blank"><?= $article->getAuthor()->getNickname() ?></a></span>
        </div>

        <?php foreach ($article::splittingEnter($article->getText()) as $paragraph): ?>
            <p><?= $paragraph ?></p>
        <?php endforeach; ?>

    </div>
    <!-- Статья -->

    <!-- Вывод одного коммента -->
    <div class="blog-post">

        <div class="post-title">
            <h2>Комментарии</h2>
        </div>

        <!-- comment -->
        <div class="comment">
            <div class="post-info">
                <span><?= $comment->getCreatedAt() ?> / by <a href="/users/Profile/Id/<?= $comment->getAuthor()->getId() ?>" target="_blank"><?= $comment->getAuthor()->getNickname() ?></a></span>
            </div>

            <?php foreach ($comment::splittingEnter($comment->getText()) as $paragraph): ?>
                <p><?= $paragraph ?></p>
            <?php endforeach; ?>

        </div>
        <!-- comment -->


        <!-- Редактировать коммент-->
        <div class="post-title">
            <h2>Редактировать комментарий</h2>
        </div>

        <?php if(!empty($error)): ?>
            <h3 class="error"><?= $error ?></h3>
        <?php endif; ?>

        <form action="/articles/Comment/Edit/<?= $comment->getId() ?>" method="POST">
            <div class="col-sm-6">
                <div class="form-group">
                    <textarea name="text"><?= $_POST['text'] ?? $comment->getText() ?></textarea><br>
                </div>
            </div>

            <a href="/articles/Id/<?= $article->getId() ?>">Отменить</a>

            <div class="text-center">
                <button type="submit" class="load-more-button">Send</button>
            </div>
        </form>
        <!-- Редактировать коммент-->

    </div>
    <!-- Вывод одного коммента -->

<?php include __DIR__ . '/../footer.php'; ?>