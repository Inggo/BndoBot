<?php

include 'core.php';

use \unreal4u\TelegramAPI\Telegram\Types\Update;

$updateData = json_decode(file_get_contents('php://input'), true);

$update = new Update($updateData);

// Check update for command
$input = explode(' ', trim($update->message->text));
$command = $input[0];
$args = array_shift($input);

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
