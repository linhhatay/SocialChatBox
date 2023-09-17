<section class="form login">
    <header>Forgot Password</header>
    <form action="<?= _WEB_ROOT . '/forgot-password' ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?php if (isset($errors)) : ?>
            <?php foreach ($errors as $field => $fieldErrors) : ?>
                <?php foreach ($fieldErrors as $error) : ?>
                    <div class="error-text">
                        <?= $error ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="field input">
            <label>Email Address</label>
            <input type="text" name="email" placeholder="Enter your email" value="<?php echo $session->old('email') ?>" required>
        </div>

        <div class="field button">
            <input type="submit" name="submit" value="Continue">
        </div>
    </form>
</section>