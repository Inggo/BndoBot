<?php

namespace Inggo\BndoBot;

use unreal4u\TelegramAPI\Telegram\Types\Update;
use Inggo\BndoBot\Commands\Dog;
use Inggo\BndoBot\Commands\FU;
use Inggo\BndoBot\Commands\RNM;
use Inggo\BndoBot\Commands\Shuffle;
use Inggo\BndoBot\Commands\ShuffleAnswer;
use Inggo\BndoBot\Commands\Hearthstone;

use Inggo\BndoBot\Trivia\Trivia;
use Inggo\BndoBot\Trivia\AnswerChecker as TriviaAnswer;

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
    public $params;

    public function __construct(Update $update)
    {
        $this->id = $update->update_id;

        $args = explode(' ', trim($update->message->text));
        $this->args = $args;
        
        $this->command = array_shift($args);

        $this->params = $args;

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
            case '/trivia':
                return new Trivia($this);
            case '/hearthstone':
                return new Hearthstone($this);
            default:
                new ShuffleAnswer($this);
                new TriviaAnswer($this);
                return;
        }
    }
}
