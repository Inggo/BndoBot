<?php

use unreal4u\TelegramAPI\Telegram\Methods\SendPhoto;
use unreal4u\TelegramAPI\TgLog;

$tgLog = new TgLog(BOT_TOKEN, $logger);
$sendPhoto = new SendPhoto();
$sendPhoto->chat_id = $chat_id;
$sendPhoto->photo = $response_img;
$tgLog->performApiRequest($sendPhoto);
