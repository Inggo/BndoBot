<?php

namespace Inggo\BndoBot\Trivia;

use Inggo\BndoBot\Commands\BaseCommand;
use Inggo\BndoBot\Shuffle\Scores;

class AnswerChecker extends BaseCommand
{
    use Scores;

    public function __construct($command)
    {
        parent::__construct($command);

        $this->setupGameFiles('trivia');

        if (!file_exists($this->gamefile) || !file_exists($this->answerfile)) {
            die();
        }

        $this->answer = implode(' ', $command->args);

        $this->run();
    }

    private function format($word)
    {
        return strtolower(trim($word));
    }

    private function checkAnswer($answer)
    {
        $answer = explode('`', $answer);

        foreach ($answer as $a) {
            if ($this->format($a) === $this->format($this->answer)) {
                return true;
            }
        }

        return false;
    }

    private function formatAnswer($answer)
    {
        $answer = explode('`', $answer);
        return $answer[0];
    }

    public function run()
    {
        $stats = file_get_contents($this->gamefile);
        $answer = file_get_contents($this->answerfile);

        if ($this->checkAnswer($answer)) {
            $points = $stats == 1 ? 5 : 5 - $stats;
            $label = $points > 1 ? 'points' : 'point';

            $this->addScore($this->command->from_id, $this->command->from, $points);

            file_put_contents($this->gamefile, '5');
            $this->unlinkIfExists($this->answerfile);
            $this->sendMessage($this->command->from . ' got it right! Answer: `' .
                $this->formatAnswer($answer) . '` for ' . $points . ' ' . $label);
            $this->showUserStats($this->command->from_id, $this->command->from);
        }
    }
}
