<?php

namespace Inggo\BndoBot\Trivia;

use Inggo\BndoBot\Commands\BaseCommand;
use Inggo\BndoBot\Trivia\Question;

use Inggo\BndoBot\Shuffle\Scores;

class Trivia extends BaseCommand
{
    use Scores;

    const SLEEP_TIME = 15;

    private $round = 0;
    private $trivia;

    public function __construct($command)
    {
        parent::__construct($command);

        $this->setupGameFiles('trivia');

        $this->run();
    }

    public function run()
    {
        $subcommand = strtolower($this->command->args[1]);
        if ($subcommand === 'stop' && file_exists($this->gamefile)) {
            return $this->endGame();
        } elseif ($subcommand === 'start' && !file_exists($this->gamefile)) {
            ini_set('max_execution_time', 0);
            $this->sendMessage('Trivia game started.');
            $this->startRound(true);
            return $this->game();
        } elseif ($subcommand === 'stats' && file_exists($this->scorefile)) {
            $this->showGameScores();
        } elseif ($subcommand === 'top10' && file_exists($this->globalscorefile)) {
            $this->showTopTen();
        } elseif ($subcommand === 'mystats' && file_exists($this->globalscorefile)) {
            $this->showUserStats($this->command->from_id, $this->command->from);
        } elseif (!file_exists($this->gamefile)) {
            $this->sendMessage('Type `/trivia start` to start a game');
        } elseif (file_exists($this->gamefile)) {
            $this->sendMessage('Game is currently running. Type `/trivia stop` to stop the game');
        } else {
            /* Ignore? */
        }

    }

    protected function endGame()
    {
        $this->sendMessage('Game stopped. Type `/trivia start` to start game.');
        $this->unlinkIfExists($this->gamefile);
        $this->unlinkIfExists($this->answerfile);
        $this->unlinkIfExists($this->scorefile);
    }

    protected function getGameState()
    {
        return file_get_contents($this->gamefile);
    }

    protected function setGameState($state, $force = false)
    {
        if (!$force && !file_exists($this->gamefile)) {
            return;
        }

        file_put_contents($this->gamefile, $state);
    }

    protected function generateAnswer()
    {
        $this->trivia = $this->getRandomQuestion();
        file_put_contents($this->answerfile, $this->trivia->answer);
        $this->sendMessage('Question: ' . $this->trivia->question . "\n" .
            'Answer: `' . str_repeat("*", strlen($this->trivia->answer)) . '`');
        $this->setGameState('1');
        sleep(self::SLEEP_TIME);
    }

    protected function showHint($count)
    {
        if (!file_exists($this->answerfile)) {
            return $this->endRound(false);
        }

        $answer = $this->getAnswer();

        $hint = '';

        for ($i = 0; $i < $count; $i++) {
            $hint .= $answer[$i];
        }

        while ($i < strlen($answer)) {
            $hint .= '*';
            $i++;
        }

        $this->sendMessage('Hint ' . $count . ': `' . $hint . '`');
        $this->setGameState($count + 1);
        sleep(self::SLEEP_TIME);
    }

    protected function getAnswer()
    {
        return file_get_contents($this->answerfile);
    }

    protected function getRandomQuestion()
    {
        // Get random file
        $files = glob('questions/questions_*');
        shuffle($files);

        $questions = file($files[0]);
        return new Question(trim($questions[rand(0, count($questions) - 1)]));
    }

    protected function startRound($force = false)
    {
        if ($this->round > 0 && $this->round % 5 === 0) {
            $this->showGameScores();
        }

        $this->round++;
        $this->sendMessage('Next question will appear in 15 seconds');
        $this->setGameState('0', $force);
        sleep(self::SLEEP_TIME);
    }

    protected function endRound($times_up = true)
    {
        if ($times_up) {
            $this->sendMessage('Times up! Answer is: `' . $this->getAnswer() . '`');
        }
        $this->setGameState('5');
        $this->unlinkIfExists($this->answerfile);
        sleep(self::SLEEP_TIME);
    }

    public function game()
    {
        if (!file_exists($this->gamefile)) {
            return;
        }

        set_time_limit(0);

        switch ($this->getGameState()) {
            case '0':
                $this->generateAnswer();
                break;
            case '1':
            case '2':
            case '3':
                $this->showHint($this->getGameState());
                break;
            case '4':
                $this->endRound();
                break;
            default:
                $this->startRound();
                break;
        }

        return $this->game();
    }
}
