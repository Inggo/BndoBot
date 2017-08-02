<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;
use Inggo\BndoBot\Shuffle\Scores;

class ShuffleAnswer extends BaseCommand
{
    use Scores;

    public function __construct($command)
    {
        parent::__construct($command);

        $this->setupGameFiles('shuffle');

        if (!file_exists($this->gamefile) || !file_exists($this->answerfile)) {
            return;
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
        $stats = file_get_contents($this->gamefile);
        $word = file_get_contents($this->answerfile);

        if ($this->format($word) === $this->format($this->answer)) {
            $points = $stats == 1 ? 5 : 5 - $stats;
            $label = $points > 1 ? 'points' : 'point';

            $this->addScore($this->command->from_id, $this->command->from, $points);

            file_put_contents($this->gamefile, '5');
            $this->unlinkIfExists($this->answerfile);
            $this->sendMessage($this->command->from . ' got it right! Answer: `' .
                $word . '` for ' . $points . ' ' . $label);
            $this->showUserStats($this->command->from_id, $this->command->from);
        }
    }
}
