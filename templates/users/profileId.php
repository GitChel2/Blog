<?php include __DIR__ . '/../header.php';
/**
 * @var \Project\Models\Users\User $userId
 */
?>

    <div class="blog-post">

        <div class="post-title">
            <h1>Профиль:</h1>
        </div>

        <div class="post-info">
            <h1>Ник: <?= $userId->getNickname() ?></h1>
            <h2><span>Дата регистрации: <?= $userId->getCreatedAt() ?></span></h2>
            <h2>Роль: <?= $userId->getRole() ?></h2>
        </div>
        <a href="/users/Articles/<?= $userId->getId()?>">Статьи автора</a>

    </div>

<?php include __DIR__ . '/../footer.php'; ?>