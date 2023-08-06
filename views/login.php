<section class="form login">
    <header>Realtime Chat App</header>
    <form action="<?= _WEB_ROOT . '/login' ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
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
        <div class="field input">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            <i class="fas fa-eye"></i>
        </div>
        <div class="link forgot-password"><a href="#' ?>">Forgot password?</a></div>

        <div class="field button">
            <input type="submit" name="submit" value="Continue to Chat">
        </div>
    </form>
    <div class="link">Not yet signed up? <a href="<?= _WEB_ROOT . '/signup' ?>">Signup now</a></div>
</section>

<script src="http://localhost/Chatbox/resources/js/pass-show-hide.js"></script>