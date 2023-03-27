<?php

require_once("utilities.php");


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (!isset($_GET['username']) || !isset($_GET['chatId'])) {
        die("invalid request");
    }

    $username = $_GET['username'];
    $chatId = $_GET['chatId'];

    if (is_null($username) || is_null($chatId))
        die("invalid request");

    $count = getUnreadMessages($server, $username, $chatId);
    echo json_encode(array("success" => true, "count" => $count));
    return $count;
}