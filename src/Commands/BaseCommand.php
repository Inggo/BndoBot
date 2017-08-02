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

    public function sendHTML($message, $reply = false)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->command->chat_id;
        if ($reply) {
            $sendMessage->reply_to_message_id = $this->command->message_id;
        }
        $sendMessage->parse_mode = 'HTML';
        $sendMessage->text = $message;
        return $tgLog->performApiRequest($sendMessage);
    }

    public function sendMessage($message, $reply = false)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->command->chat_id;
        if ($reply) {
            $sendMessage->reply_to_message_id = $this->command->message_id;
        }
        $sendMessage->parse_mode = 'Markdown';
        $sendMessage->text = $message;
        return $tgLog->performApiRequest($sendMessage);
    }

    public function respond($message, $reply = false)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $this->command->chat_id;
        if ($reply) {
            $sendMessage->reply_to_message_id = $this->command->message_id;
        }
        $sendMessage->text = $message;
        $tgLog->performApiRequest($sendMessage);
    }

    public function respondWithPhoto($image, $reply = false)
    {
        $tgLog = new TgLog(BOT_TOKEN);
        $sendPhoto = new SendPhoto();
        $sendPhoto->chat_id = $this->command->chat_id;
        if ($reply) {
            $sendPhoto->reply_to_message_id = $this->command->message_id;
        }
        $sendPhoto->photo = $image;
        $tgLog->performApiRequest($sendPhoto);
    }
}
