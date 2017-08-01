<?php

define('RNM_BASE_URL', 'https://rate.nyo.me');

$url = RNM_BASE_URL . '/random';

$tag = $arg;

if ($tag) {
    $url .= '/' . $tag;
}

$url .= '.json';

# file_put_contents('test.in', "Requesting " . $url . "\n", FILE_APPEND);

$response = file_get_contents($url);
$json_response = json_decode($response);

$response_msg = $json_response->id ?
    RNM_BASE_URL . '/' . $json_response->id :
    'Try again later....';

# file_put_contents('test.in', $response_img . "\n", FILE_APPEND);

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

$tgLog = new TgLog(BOT_TOKEN, $logger);
$sendMessage = new SendMessage();
$sendMessage->chat_id = $update->message->chat->id;
$sendMessage->text = $response_msg;
$tgLog->performApiRequest($sendMessage);
