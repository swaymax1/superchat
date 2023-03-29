<?php

require_once("utilities.php");
require_once("server.php");

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['message_upload'])) {
        $sender = sanitizeSql($server, strval($data['sender']));
        $chatId = sanitizeSql($server, strval($data['chatId']));
        $content = $data['content'];

        $timestamp = saveMessage($server, $chatId, $sender, $content);
        echo json_encode(array("success" => true, "timestamp" => $timestamp));
    } else
        echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Content-Type: application/json");

    $chatId = sanitizeSql($server, strval($_GET['chatId']));
    $since = sanitizeSql($server, strval($_GET['since']));
    $messages = getMessages($server, $chatId, $since, $_SESSION['username']);

    if ($messages === "error") {
        echo json_encode(array("success" => false));
    } else {
        echo json_encode(array("success" => true, "messages" => $messages));
    }

}