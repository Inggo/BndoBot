<?php

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

$tgLog = new TgLog(BOT_TOKEN, $logger);
$sendMessage = new SendMessage();
$sendMessage->chat_id = $chat_id;
$sendMessage->text = $response_msg;
$tgLog->performApiRequest($sendMessage);
