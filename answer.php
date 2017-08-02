<?php

define('SHUFFLE_CHAT_ID', $chat_id);

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

function sendMessage($msg)
{
    $tgLog = new TgLog(BOT_TOKEN, $logger);
    $sendMessage = new SendMessage();
    $sendMessage->chat_id = SHUFFLE_CHAT_ID;
    $sendMessage->parse_mode = 'Markdown';
    $sendMessage->text = $msg;
    return $tgLog->performApiRequest($sendMessage);
}

$game = '.shuffle-' . $chat_id;

// If game is not running, stop
if (!file_exists($game)) {
    die();
}

// If word has been answered, stop
if (!file_exists($game . '-word')) {
    die();
}

function fixFormat($word)
{
    return strtolower(trim($word));
}

function checkAnswer($game, $answer, $from)
{
    $word = file_get_contents($game . '-word');

    if (fixFormat($word) === fixFormat($answer)) {
        file_put_contents($game, '5');
        unlink($game . '-word');
        sendMessage($from . ' got it right! Answer: `' . $word . '`');
    }
}

checkAnswer($game, $command, $from);
