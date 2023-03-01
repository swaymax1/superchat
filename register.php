<?php

include_once("./php/utilities.php");

session_start();
$message = "";
if (isset($_SESSION['wrong_credentials']) && $_SESSION['wrong_credentials']) {
    $message = $_GET['message'];
    $email = $_GET['email'];
    $username = $_GET['username'];
    $password = $_GET['password'];
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
    <form class="form" id="form" method="post" action="./php/register.inc.php" id="register">
        <div class="container" id="register">
            <h1 class="form__title">Create Account</h1>
            <div class="form__message form__message-error" id="form-message">
                <?php echo $message ?>
            </div>
            <div class="form__input-group">
                <input class="form__input" type="email" placeholder="Email" name="email"
                    value="<?php echo isset($email) ? $email : "" ?>">
            </div>
            <div class="form__input-group">
                <input class="form__input" type="text" placeholder="Username" name="username"
                    value="<?php echo isset($username) ? $username : "" ?>">
            </div>
            <div class="form__input-group">
                <input class="form__input" type="password" placeholder="Password" name="password"
                    value="<?php echo isset($password) ? $password : "" ?>">
            </div>
            <div class="form__input-group">
                <input class="form__input" type="password" placeholder="Confirm Password" name="confirm-password"
                    value="<?php echo isset($password) ? $password : "" ?>">
            </div>
            <button type="submit" class="form__button" id="register-submit">Create Account</button>
            <p class="form__text">
                <a class="form__link" href="login.html" id="loginLink">Already have an account? Login</a>
            </p>
            <input type="hidden" name="register">
        </div>
    </form>
    <script src="./js/main.js"></script>
</body>

</html>