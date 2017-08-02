<?php

namespace Inggo\BndoBot\Commands;

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\Telegram\Methods\SendPhoto;
use unreal4u\TelegramAPI\TgLog;

class BaseCommand
{
    public $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function sendMessage($message)
    {
        $tgLog = new TgLog(BOT_TOKEN, $logger);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->command->chat_id;
        $sendMessage->parse_mode = 'Markdown';
        $sendMessage->text = $message;
        return $tgLog->performApiRequest($sendMessage);
    }

    public function respond($message)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->command->chat_id;
        $sendMessage->text = $message;
        $tgLog->performApiRequest($sendMessage);
    }

    public function respondWithPhoto($image)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendPhoto = new SendPhoto();
        $sendPhoto->chat_id = $this->command->chat_id;
        $sendPhoto->photo = $image;
        $tgLog->performApiRequest($sendPhoto);
    }
}
