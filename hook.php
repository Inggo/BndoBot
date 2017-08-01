<?php

include 'core.php';

use \unreal4u\TelegramAPI\Telegram\Types\Update;

$updateData = json_decode(file_get_contents('php://input'), true);

$update = new Update($updateData);

if ($update->update_id === file_get_contents('.lastupdate')) {
    die();
}

file_put_contents('.lastupdate', $update->update_id);

// Check update for command
$args = explode(' ', trim($update->message->text));
$command = $args[0];

$chat_id = $update->message->chat->id;

if ($update->message->from) {
    $from = $update->message->from->username ?:
        $update->message->from->first_name;

    $from_full = $update->message->from->username ?:
        $update->message->from->first_name . ' ' .
        $update->message->from->last_name;

    $from_id = $update->message->from->id;
}

// Run command
switch ($command) {
    case '/shuffle':
        include 'shuffle.php';
        break;
    case '/fu':
        include 'fu.php';
        break;
    case '/rnm':
        include 'rnm.php';
        break;
    default:
        include 'answer.php';
        break;
}
