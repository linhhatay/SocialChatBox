<section class="chat-area">
    <header>
        <?php
        // $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
        // $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
        // if (mysqli_num_rows($sql) > 0) {
        //     $row = mysqli_fetch_assoc($sql);
        // } else {
        //     header("location: users.php");
        // }
        ?>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg" alt="">
        <div class="details">
            <span>Linh Nguyễn</span>
            <p>Active now</p>
        </div>
    </header>
    <div class="chat-box">

    </div>
    <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
    </form>
</section>