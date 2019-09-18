<h1>Update</h1>

<form method="post" action="" enctype="multipart/form-data">
    <label for="login">Identifiant</label>
    <input type="text" name="login" id="login" value="<?= $login ?>" required>
    <br>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="<?= $email ?>" required>
    <br>
    <input type="file" name="avatar" id="avatar">
    <br>
    <?php if ($avatar != ''): ?>
        <img src="<?= $avatar ?>" alt="<?= $login ?? '' ?>">
    <?php endif ?>

    <input type="submit" value="Ok">
</form>
