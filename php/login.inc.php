<?php

require_once 'server.php';
require_once 'utilities.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = sanitizeSql($server, $_POST['username']);
        $password = sanitizeSql($server, $_POST['password']);

        global $salt1, $salt2;
        $password = hash('ripemd128', $salt1 . $password . $salt2);
        $exists = checkColumn($server, array('username' => $username, 'password' => $password));

        if ($exists) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = getEmailFromUsername($server, $username);
            $_SESSION['loggedIn'] = true;
            header("Location: ../chats.php");
        } else {
            $_SESSION['loggedIn'] = false;
            $_SESSION['wrong_credentials'] = true;
            header("Location: ../login.php");
        }
    }
}
