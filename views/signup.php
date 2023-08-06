<section class="form signup">
    <header>Realtime Chat App</header>
    <form action="<?= _WEB_ROOT . '/signup' ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
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
        <div class="name-details">
            <div class="field input">
                <label>First Name</label>
                <input type="text" name="fname" placeholder="First name" value="<?php echo $session->old('fname') ?>" required>
            </div>
            <div class="field input">
                <label>Last Name</label>
                <input type="text" name="lname" placeholder="Last name" value="<?php echo $session->old('lname') ?>" required>
            </div>
        </div>
        <div class="field input">
            <label>Email Address</label>
            <input type="text" name="email" placeholder="Enter your email" value="<?php echo $session->old('email') ?>" required>
        </div>
        <div class="field input">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter new password" required>
            <i class="fas fa-eye"></i>
        </div>
        <div class="field image">
            <label>Select Image</label>
            <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg">
        </div>
        <div class="field button">
            <input type="submit" name="submit" value="Continue to Chat">
        </div>
    </form>
    <div class="link">Already signed up? <a href="<?= _WEB_ROOT . '/login' ?>">Login now</a></div>
</section>

<script src='http://localhost/Chatbox/resources/js/pass-show-hide.js'></script>