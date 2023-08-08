<section class="users">
    <header>
        <div class="content">
            <?php
            if (!$session->has('user')) {
                header("Location: " . _WEB_ROOT . "/login");
                exit();
            }
            ?>
            <img src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg" alt="Avatar">
            <div class="details">
                <span><?= $session->get('user')['fname'] . ' ' . $session->get('user')['lname'] ?></span>
                <p><?= $session->get('user')['status'] ?></p>
            </div>
        </div>
        <a href="<?= _WEB_ROOT . '/logout' ?>" class="logout">Logout</a>
    </header>
    <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
    </div>
    <div class="users-list">

    </div>
</section>

<script src="http://localhost/Chatbox/resources/js/user.js"></script>