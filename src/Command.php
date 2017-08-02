<?php

namespace Inggo\BndoBot;

use unreal4u\TelegramAPI\Telegram\Types\Update;
use Inggo\BndoBot\Commands\Dog;
use Inggo\BndoBot\Commands\FU;
use Inggo\BndoBot\Commands\RNM;
use Inggo\BndoBot\Commands\Shuffle;
use Inggo\BndoBot\Commands\ShuffleAnswer;

class Command
{
    public $id;
    public $args;
    public $command;
    public $from;
    public $from_first;
    public $from_full;
    public $from_id;
    public $chat_id;
    public $update;

    public function __construct(Update $update)
    {
        $this->id = $update->update_id;

        $this->args = explode(' ', trim($update->message->text));
        $this->command = $this->args[0];

        $this->chat_id = $update->message->chat->id;

        $this->from = $update->message->from->username ?:
            $update->message->from->first_name;

        $this->from_first = $update->message->from->first_name;

        $this->from_full = $update->message->from->first_name . ' ' .
            $update->message->from->last_name;

        $this->from_id = $update->message->from->id;
    }

    public function run()
    {
        if ($this->id == file_get_contents('.lastupdate')) {
            return;
        }

        file_put_contents('.lastupdate', $this->id);

        switch ($this->command) {
            case '/dog':
                return new Dog($this);
            case '/fu':
                return new FU($this);
            case '/rnm':
                return new RNM($this);
            case '/shuffle':
                return new Shuffle($this);
            default:
                return new ShuffleAnswer($this);
        }
    }
}
