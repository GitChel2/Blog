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

        <?php
        if ($user !== null)
            if(\Project\Models\Articles\Article::possibilityChange($article, $user)):
        ?>
                <a href="/articles/Edit/<?= $article->getId() ?>">Редактировать</a>
                <h1></h1>
                <a href="/articles/Delete/<?= $article->getId() ?>">Удалить</a>

        <?php endif; ?>


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

    <!-- Вывод всех комментов -->
    <div class="blog-post">

        <div class="post-title">
            <h2>Комментарии</h2>
        </div>

        <?php
        $comments = $article->getСomments();
        if ($comments === null):
        ?>
            <p>Пока комментариев нет</p>
        <?php
        else:
            foreach ($comments as $comment ):
        ?>
                <!-- comment -->
                <div class="comment">
                    <div class="post-info">
                        <span><?= $comment->getCreatedAt() ?> / by <a href="/users/Profile/Id/<?= $comment->getAuthor()->getId() ?>" target="_blank"><?= $comment->getAuthor()->getNickname() ?></a></span>
                    </div>

                    <?php foreach ($comment::splittingEnter($comment->getText()) as $paragraph): ?>
                        <p><?= $paragraph ?></p>
                    <?php endforeach; ?>

                    <?php
                    if ($user !== null)
                        if(\Project\Models\Articles\Comments::possibilityChange($comment, $user)):
                    ?>
                            <a href="/articles/Comment/Edit/<?= $comment->getId() ?>">Редактировать</a>
                            <a href="/articles/Comment/Delete/<?= $comment->getId() ?>">Удалить</a>
                    <?php endif; ?>

                </div>
                <!-- comment -->

            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Писать коммент-->
        <?php if(!empty($user)): ?>

            <div class="post-title">
                <h2>Написать комментарий</h2>
            </div>

            <?php if(!empty($error)): ?>
                <h3 class="error"><?= $error ?></h3>
            <?php endif; ?>

            <form action="/articles/Comment/Add/<?= $article->getId() ?>" method="POST">
                <div class="col-sm-6">
                    <div class="form-group">
                        <textarea name="text"><?= $_POST['text'] ?? '' ?></textarea><br>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="load-more-button">Send</button>
                </div>
            </form>

        <?php else: ?>
            <h3>Чтобы написать комментарий, авторизируйтесь!</h3>
        <?php endif; ?>
        <!-- Писать коммент-->

    </div>
    <!-- Вывод всех комментов -->


<?php include __DIR__ . '/../footer.php'; ?>

