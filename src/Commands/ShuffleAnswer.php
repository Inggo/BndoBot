<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class ShuffleAnswer extends BaseCommand
{
    public function __construct($command)
    {
        parent::__construct($command);

        $this->gamefile = '.shuffle-' . $chat_id;
        $this->wordfile = $this->gamefile . '-word';

        if (!file_exists($this->gamefile) || !file_exists($this->wordfile)) {
            die();
        }

        $this->answer = implode(' ', $command->args);

        $this->run();
    }

    private function format($word)
    {
        return strtolower(trim($word));
    }

    public function run()
    {
        $word = file_get_contents($this->wordfile);

        if ($this->format($word) === $this->format($this->answer)) {
            file_put_contents($this->gamefile, '5');
            unlink($this->wordfile);
            $this->sendMessage($this->command->from ' got it right! Answer: `' . $word . '`');
        }
    }
}
