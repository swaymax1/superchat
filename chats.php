<?php

require_once("./php/utilities.php");
require_once("./php/server.php");

session_start();

if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header("Location: login.php");
}

// chats is a 2d array of username and chat id
$chats = getChats($server, $_SESSION['username']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./styles/chats-list.css" rel="stylesheet">
    <title>Chats</title>
</head>

<body>
    <input type="hidden" id="username-div" value="<?php echo $_SESSION['username'] ?>">
    <div id="chats-list">
        <div class="header">Chats</div>
        <?php foreach ($chats as $chat) { ?>
            <div class="chat" data-id="<?php echo $chat['chatId'] ?>" data-user2="<?php echo $chat['username']; ?>">
                <div class="chat-info">

                    <!-- header -->
                    <div class="chat-header">
                        <div class="chat-username">
                            <?php echo $chat['username'] ?>
                        </div>
                        <div class="chat-time">
                            <?php echo $chat['last_message'] ?>
                        </div>
                    </div>
                    <!-- header -->
                    <div class="unseen-messages" style="visibility: hidden;"
                        id="<?php echo $chat['chatId'] . '-unseen' ?>">
                        <?php echo $chat['unseen_messages'] ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div id="add-user-error-message"></div>
    <div id="add-user">
        <input type="text" id="add-user-field" autocomplete="off" placeholder="Add a user">
        <input type="submit" id="add-user-submit" value="Start chat">
    </div>
    <script src="./js/chats.js"></script>
</body>

</html>