<?php

require_once 'server.php';
require_once 'utilities.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['register'])) {
        $message = "";
        if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['username'])) {
            $message = "error";
        }

        $email = sanitizeSql($server, $_POST['email']);
        $password = sanitizeSql($server, $_POST['password']);
        $username = sanitizeSql($server, $_POST['username']);

        $data = "email=$email&password=$password&username=$username";

        if (!checkLength($email) || !checkLength($password) || !checkLength($username)) {
            $message = "Invalid credentials";
        } elseif (!checkEmail($email)) {
            $message = "Invalid email address";
        } elseif (checkColumn($server, array("email" => $email))) {
            $message = "Email already in use";
        } elseif (checkColumn($server, array("username" => $username))) {
            $message = "Username already in use";
        }
        if ($message !== '') {
            $_SESSION['wrong_credentials'] = true;
            $_SESSION['loggedIn'] = false;
            header("Location: ../register.php?message=$message&$data");
        } else {
            if (registerUser($server, $email, $password, $username)) {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['wrong_credentials'] = false;
                $_SESSION['loggedIn'] = true;
                header("Location: ../chat.php");
            } else {
                $_SESSION['loggedIn'] = false;
                header("Location: ../register.php?message=error&$data");
            }
        }

        mysqli_close($server);
    }
}

function registerUser($server, $email, $password, $username)
{
    global $salt1, $salt2;
    $stmt = mysqli_prepare($server, "INSERT INTO users(email, password, username) VALUES(?,?,?)");
    $password = hash('ripemd128', $salt1 . $password . $salt2);
    mysqli_stmt_bind_param($stmt, "sss", $email, $password, $username);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        $response = false;
    } else {
        $response = true;
    }
    mysqli_stmt_close($stmt);
    return $response;
}