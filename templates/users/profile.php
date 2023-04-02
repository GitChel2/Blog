<?php include __DIR__ . '/../header.php';
/**
 * @var \Project\Models\Users\User $user
 */
?>

    <div class="blog-post">

        <div class="post-title">
            <h1>Ваш профиль:</h1>
        </div>

        <div class="post-info">
            <h1>Ник: <?= $user->getNickname() ?></h1>
            <h2><span>Дата регистрации: <?= $user->getCreatedAt() ?></span></h2>

            <h2>Почта: <?= $user->getEmail() ?></h2>
            <h2>Роль: <?= $user->getRole() ?></h2>
        </div>
        <a href="/users/Articles/<?= $user->getId()?>">Мои статьи</a>

    </div>
<?php include __DIR__ . '/../footer.php'; ?>