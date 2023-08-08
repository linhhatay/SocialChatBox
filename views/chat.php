<section class="chat-area">
    <header>
        <?php

        ?>
        <a href="<?= _WEB_ROOT . '/home' ?>" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg" alt="">
        <div class="details">
            <span><?= $user['fname'] . ' ' . $user['lname'] ?></span>
            <p><?= $user['status'] ?></p>
        </div>
    </header>
    <div class="chat-box">

    </div>
    <form action="<?= _WEB_ROOT . '/chat/insert' ?>" class="typing-area">
        <input type="text" class="outgoing_id" name="outgoing_id" value="<?php echo $session->get('user')['unique_id']  ?>" hidden>
        <input type="text" class="incoming_id" name="incoming_id" value="<?= $user['unique_id'] ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
    </form>
</section>

<script src="http://localhost/Chatbox/resources/js/chat.js"></script>