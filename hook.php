<?php

include 'core.php';

use \unreal4u\TelegramAPI\Telegram\Types\Update;

$updateData = json_decode(file_get_contents('php://input'), true);

$update = new Update($updateData);

// Check update for command
$args = explode(' ', trim($update->message->text));
$command = $args[0];

$chat_id = $update->message->chat->id;

// Run command
switch ($command) {
    case '/fu':
        include 'fu.php';
        break;
    case '/rnm':
        include 'rnm.php';
        break;
    default:
}
