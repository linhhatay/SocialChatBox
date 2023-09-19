<?php

use App\Session;

$session = Session::getInstance();
$uniqueId = $session->get('user')['unique_id'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="icon" href="http://localhost/Chatbox/public/icons/logo-chatbox.png" type="image/png">
    <link rel="stylesheet" href="http://localhost/Chatbox/resources/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <?php
    if ($uniqueId) {
        echo '<script defer>
        var conn = new WebSocket("ws://localhost:8080");
        conn.onopen = function(e) {
            const loginMessage = JSON.stringify({
                type: "login",
                userId: ' . $uniqueId . ',
            });
            conn.send(loginMessage);

            console.log("Connection established!");
        };
        </script>';
    }
    ?>
</head>

<body>
    <div class="wrapper">
        <?php
        require_once $viewPath;
        ?>
    </div>

    <?php
    if ($uniqueId) {
        echo '<script defer>
        const logoutBtn = document.querySelector(".logout");
        if(logoutBtn) {
            logoutBtn.onclick = function() {
                const logoutMessage = JSON.stringify({
                    type: "logout",
                    userId: ' . $uniqueId . ',
                });
                conn.send(logoutMessage);
                conn.close();
            }
        }
        </script>';
    }
    ?>
</body>

</html>