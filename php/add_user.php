<?php

require_once("utilities.php");
require("server.php");

if (isset($_GET['add_user']) && isset($_GET['user1']) && isset($_GET['user2'])) {
    $user1 = sanitizeSql($server, $_GET['user1']);
    $user2 = sanitizeSql($server, $_GET['user2']);

    if ($user1 === $user2 || !checkColumn($server, ["username" => $user2])) {
        echoMessage(false, array("message" => "user not found"));
        exit();
    }

    $room = checkRoom($server, $user1, $user2);
    if ($room === 'error') {
        echoMessage(false, array("message" => "internal error"));
    } elseif ($room) {
        echoMessage(true, array("id" => $room['id']));
    } else {
        $room_create = createRoom($server, $user1, $user2);
        if ($room_create === "error") {
            echoMessage(false, ["message" => "internal error"]);
        } else {
            echoMessage(true, ["id" => $room_create]);
        }
    }

}


function createRoom($server, $username1, $username2)
{
    $query = "INSERT INTO chats(username1, username2) VALUES(?, ?)";
    $stmt = mysqli_prepare($server, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username1, $username2);
    if (!mysqli_stmt_execute($stmt))
        return "error";
    $room = checkRoom($server, $username1, $username2);
    return $room['id'];
}