<?php

include 'core.php';

use \unreal4u\TelegramAPI\Telegram\Types\Update;

$updateData = json_decode(file_get_contents('php://input'), true);

$update = new Update($updateData);

// Check update for command
file_put_contents('test.in', $update);
