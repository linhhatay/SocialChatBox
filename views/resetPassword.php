<section class="form login">
    <header>Reset Password</header>
    <form action="<?= _WEB_ROOT . '/send-reset-password/' . $token  ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
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
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" value="" required>
            <i class="fas fa-eye"></i>
        </div>
        <div class="field input">
            <label>Password confirm</label>
            <input type="password" name="passwordConfirm" placeholder="Enter your password confirm" required>
            <i class="fas fa-eye"></i>
        </div>

        <div class="field button">
            <input type="submit" name="submit" value="Continue">
        </div>
    </form>
    <div class="link"><a href="<?= _WEB_ROOT . '/login' ?>">Login</a></div>
</section>

<script src="http://localhost/Chatbox/resources/js/pass-show-hide.js"></script>