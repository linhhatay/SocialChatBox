<section class="users">
    <header>
        <div class="content">
            <img src="<?= $session->get('user')['img'] ?>" alt="Avatar">
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