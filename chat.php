<?php

session_start();
if (!isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'] == true) {
  header("Location: login.php");
}

if (!isset($_GET['chatId']) || $_GET['chatId'] == null) {
  die("Error");
}

$_SESSION['chatId'] = $_GET['chatId'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="styles/chat.css" rel="stylesheet">
</head>

<body>
  <div style="display: none;" id="credentials">
    <?php echo json_encode(array("email" => $_SESSION['email'], "username" => $_SESSION['username'], "chatId" => $_GET['chatId'], "user2" => $_GET['user2'])); ?>
  </div>

  <div id="container">
    <div id="messages_container">
    </div>
    <form id="message_form" action="#" method="post" placeholder="Type message here" autocomplete="off">
      <input type="text" id="message_field">
      <input type="submit" value="SEND" id="message_submit">
    </form>
  </div>
  <script src="./js/chat.js"></script>
</body>

</html>
