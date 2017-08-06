<?php
if (isset($update['update']['message']['message'])) {
    $msg = $update["update"]["message"]["message"];
}
if (isset($update['update']['message']['to_id']['channel_id'])) {
    $chatID = 'channel#' . $update['update']['message']['to_id']['channel_id'];
    $type   = "supergroup";
} //supergroup
if (isset($update['update']['message']['to_id']['chat_id'])) {
    $chatID = 'chat#' . $update['update']['message']['to_id']['chat_id'];
    $type   = 'group';
} //group
if (isset($update['update']['message']['to_id']['user_id'])) {
    $chatID = $update['update']['message']['from_id'];
    $type   = 'private';
} //private chat
if (isset($update['update']['message']['from_id'])) {
    $userID = $update['update']['message']['from_id'];
} //id user
if (isset($update['update']['message']['id'])){
    $msgID=$update['update']['message']['id'];
}

if (isset($update['update']['message']['date'])) {
    if (mindate(time()) == mindate($update['update']['message']['date'])) {
        include 'main.php';
        echo 'Im including
        ';
    } else {
        echo "I'm receving old messages
        ";
    }
}