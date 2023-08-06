<section class="users">
    <header>
        <div class="content">
            <?php
            // $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
            // if(mysqli_num_rows($sql) > 0){
            //   $row = mysqli_fetch_assoc($sql);
            // }
            ?>
            <img src="https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fKbMhZMIb07mCJ6esXL.jpg" alt="">
            <div class="details">
                <span><?= $user['fname'] . ' ' . $user['lname'] ?></span>
                <p><?= $user['status'] ?></p>
            </div>
        </div>
        <a href="php/logout.php?logout_id" class="logout">Logout</a>
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