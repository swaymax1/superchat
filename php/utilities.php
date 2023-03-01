<?php

require_once("server.php");

$salt1 = 'qm(2h*';
$salt2 = 'pg!r,';



function sanitizeString($string)
{
    return stripslashes(htmlentities(strip_tags($string)));
}

function sanitizeSql($connection, $string)
{
    return sanitizeString(mysqli_real_escape_string($connection, $string));
}

function checkColumn($server, $columns)
{
    $whereClause = array();
    $params = "";
    foreach ($columns as $column => $value) {
        array_push($whereClause, "$column = ?");
        switch (getType($value)) {
            case "integer":
                $params .= "i";
                break;
            case "string":
                $params .= "s";
                break;
            case "double":
                $params .= "d";
                break;
        }
    }

    $whereClause = implode(" AND ", $whereClause);
    $query = "SELECT * FROM users WHERE $whereClause";
    $stmt = mysqli_prepare($server, $query);
    $columns = array_values($columns);
    mysqli_stmt_bind_param($stmt, $params, ...$columns);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    return mysqli_stmt_num_rows($stmt) > 0;
}


function checkColumnUnprepared($server, $columns)
{
    $whereClause = array();

    foreach ($columns as $column => $value) {
        array_push($whereClause, "$column='$value'");
    }

    $whereClause = implode(" AND ", $whereClause);

    $result = mysqli_query($server, "SELECT * FROM users WHERE $whereClause");

    return mysqli_num_rows($result) > 0;
}


function getEmailFromUsername($server, $username)
{
    $stmt = mysqli_prepare($server, "SELECT email FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);
    $username = mysqli_fetch_array($result)[0];
    return $username;
}


function destroySession()
{
    $_SESSION = array();
    setcookie(session_name(), '', time() - 260000);
    session_destroy();
}

function checkLength($string)
{
    if (empty($string) || $string < 5)
        return false;
    return true;
}

function checkEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function getChats($server, $username)
{
    $query = "SELECT * FROM chats WHERE username1 = ? or username2 = ?";
    $stmt = mysqli_prepare($server, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_errno($stmt)) {
        return "error";
    }

    $result = mysqli_stmt_get_result($stmt);
    $data = array();
    while (($row = mysqli_fetch_assoc($result)) != null) {
        $last_message = configureLastMessageTime($row['last_message']);
        if ($row['username1'] === $username) {
            array_push($data, array("chatId" => $row['id'], "username" => $row['username2'], "last_message" => $last_message, "unseen_messages" => getUnreadMessages($server, $username, $row['id'])));
        } elseif ($row['username2'] === $username) {
            array_push($data, array("chatId" => $row['id'], "username" => $row['username1'], "last_message" => $last_message, "unseen_messages" => getUnreadMessages($server, $username, $row['id'])));
        }
    }
    return $data;
}

function saveMessage($server, $chatId, $sender, $message)
{
    $query = "INSERT INTO messages(chat_id, sender_id, content) VALUES(?,?,?)";
    $stmt = mysqli_prepare($server, $query);

    mysqli_stmt_bind_param($stmt, "iss", $chatId, $sender, $message);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_errno($stmt))
        die("error");
    updateLastMessageTime($server, $chatId);
    $messageId = mysqli_stmt_insert_id($stmt);
    if ($messageId == 0) {
        die("error");
    } else {
        updateMessageSeenStatus($server, $messageId, $sender, $chatId);
    }
}

function getMessages($server, $chatId, $since, $username)
{

    $stmt = mysqli_prepare($server, "SELECT * FROM messages WHERE chat_id = ? AND UNIX_TIMESTAMP(created_at) > ? ORDER BY created_at ASC");

    mysqli_stmt_bind_param($stmt, "ii", $chatId, $since);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
    foreach ($messages as $message) {
        updateMessageSeenStatus($server, $message['id'], $username, $chatId);
    }

    mysqli_stmt_close($stmt);
    return $messages;
}

function updateMessageSeenStatus($server, $id, $username, $chatId)
{
    $stmt = mysqli_prepare($server, "INSERT INTO seen_messages (message_id, username, chat_id)
    SELECT ?, ?, ?
    FROM DUAL
    WHERE NOT EXISTS (
      SELECT *
      FROM seen_messages
      WHERE message_id = ? AND username = ?
    )");

    mysqli_stmt_bind_param($stmt, "isiis", $id, $username, $chatId, $id, $username);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt)) {
        die("error");
    }
}


function updateLastMessageTime($server, $id)
{
    $query = "UPDATE chats set last_message = CURRENT_TIMESTAMP WHERE id = ?";
    $stmt = mysqli_prepare($server, $query);
    mysqli_stmt_bind_param($stmt, "d", $id);
    mysqli_execute($stmt);
    if (mysqli_stmt_errno($stmt))
        die("error");
}

function configureLastMessageTime($time)
{
    $datetime = new DateTime($time);
    $currentDatetime = new DateTime();

    if ($datetime->format('Y') < $currentDatetime->format('Y')) {
        return $datetime->format('M/Y');
    } else if ($datetime->format('Y-m-d') < $currentDatetime->format('Y-m-d')) {
        return $datetime->format('d/M');
    } else {
        return strtoupper($datetime->format('g:i a'));
    }
}


function checkLoggedIn($session)
{
    if (!isset($session['loggedIn']) || !$session['loggedIn']) {
        echo json_encode(array("success" => false, "message" => "user not logged in"));
        exit();
    }
}

function echoMessage($success, $params)
{
    echo json_encode(["success" => $success, ...$params]);
}


function checkRoom($server, $user1, $user2)
{
    $query = "select * from chats where username1 = ? and username2 = ? or username2 = ? or username1 = ?";
    $stmt = mysqli_prepare($server, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $user1, $user2, $user1, $user2);
    if (!mysqli_stmt_execute($stmt))
        return "error";
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getUnreadMessages($server, $username, $chatId) {
    $query = "SELECT COUNT(*) as count FROM messages WHERE chat_id = ? AND id NOT IN (SELECT message_id FROM seen_messages WHERE username = ? AND chat_id = ?)";
    $stmt = mysqli_prepare($server, $query);
    mysqli_stmt_bind_param($stmt, "iss", $chatId, $username, $chatId);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_errno($stmt)) {
        die("error");
    }
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    return $data['count'];
}

