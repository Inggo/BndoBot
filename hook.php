<?php

include 'core.php';

use \unreal4u\TelegramAPI\Telegram\Types\Update;

$updateData = json_decode(file_get_contents('php://input'), true);

$update = new Update($updateData);

$db = sqlite_open('bndobot', 0666, $sqliteerror);

if (!$db) {
    die($sqliteerror);
}

$query = sqlite_query($db, "SELECT * FROM updates WHERE id = {$update->update_id}");
$result = sqlite_fetch_all($query, SQLITE_ASSOC);

if (count($result) > 0) {
    die();
}

$query = sqlite_query($db, "INSERT INTO updates VALUES ({$update->update_id})");

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
