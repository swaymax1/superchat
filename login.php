<?php

require_once("./php/utilities.php");
session_start();

if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    header("Location: chats.php");
}

$message = "";
if (isset($_SESSION['wrong_credentials']) && $_SESSION['wrong_credentials'] == true) {
    $message = "Wrong username or password";
    destroySession();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/form.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <form class="form" method="post" action="./php/login.inc.php" id="form">

        <div class="container" id="login">
            <h1 style="position:relative;left:30%;color:lightgreen">Superchat</h1>
            <h2 class="form__title">Login</h2>
            <div class="form__message form__message-error" id="form-message">
                <?php echo $message; ?>
            </div>
            <div class="form__input-group">
                <input class="form__input" name="username" id="username" type="text" placeholder="Username">
            </div>
            <div class="form__input-group">
                <input class="form__input" name="password" id="password" type="password" placeholder="Password">
            </div>
            <button type="submit" id="login-submit" class="form__button">Login</button>
            <p class="form__text">
                <a class="form__link" href="#">Forgot your password? Click here</a>
            </p>
            <p>
                <a class="form__link" href="register.php" id="createAccountLink">Don't have an account? Create
                    Account</a>
            </p>
            <input type="hidden" name="login">
    </form>
    </div>
    <script src="js/main.js"></script>
</body>

</html>