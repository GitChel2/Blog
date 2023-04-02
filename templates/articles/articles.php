<?php
include __DIR__ . '/../header.php';
/**
 * @var \Project\Models\Articles\Article $article
 */

/**
 * @var \Project\Models\Users\User $user
 */

    foreach ($articles as $article):
?>

        <div class="white-spacing">
            <!-- Blog Post Start -->
            <div class="col-md-12 blog-post">
                <div class="post-title">
                    <a href="/articles/Id/<?= $article->getId() ?>"><h1><?= mb_substr($article->getName(), 0, 54) . ' ...' ?></h1></a>
                </div>
                <div class="post-info">
                    <span><?= $article->getCreatedAt() ?> / by <a href="/users/Profile/Id/<?= $article->getAuthor()->getId() ?>"><?= $article->getAuthor()->getNickname() ?></a></span>
                </div>

                <?php
                    foreach ($article::splittingEnter(mb_substr($article->getText(), 0, 502) . ' ...') as $paragraph):
                ?>
                    <p><?= $paragraph ?></p>
                <?php endforeach; ?>

                <a href="/articles/Id/<?= $article->getId() ?>" class="button button-style button-anim fa fa-long-arrow-right"><span>Читать далее</span></a>
            </div>
            <!-- Blog Post End -->
        </div>

    <?php endforeach; ?>

    <div class="col-md-12 text-center">
        <a href="javascript:void(0)" id="load-more-post" class="load-more-button">Load</a>
        <div id="post-end-message"></div>
    </div>

<?php include __DIR__ . '/../footer.php'; ?>