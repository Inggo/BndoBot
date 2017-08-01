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

$img = $json_response->content->upload->links->original;
$response_img = RNM_BASE_URL . $img;

# file_put_contents('test.in', $response_img . "\n", FILE_APPEND);

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

$tgLog = new TgLog(BOT_TOKEN, $logger);
$sendMessage = new SendMessage();
$sendMessage->chat_id = $update->message->chat->id;
$sendMessage->text = $response_img;
$tgLog->performApiRequest($sendMessage);
