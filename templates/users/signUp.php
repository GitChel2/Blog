<?php include __DIR__ . '/../header.php'; ?>

    <form action="/users/register" method="POST">

        <?php if (!empty($error)): ?>
            <h3 class="error"><?= $error ?></h3>
        <?php endif; ?>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Nickname" name="nickname" value="<?= $_POST['nickname'] ?? '' ?>">
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Email" name="email" value="<?= $_POST['email'] ?? '' ?>">
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" value="<?= $_POST['password'] ?? '' ?>">
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password Confirmation" name="passwordConfirmation" value="<?= $_POST['passwordConfirmation'] ?? '' ?>">
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="load-more-button">Send</button>
        </div>

    </form>

<?php include __DIR__ . '/../footer.php'; ?>