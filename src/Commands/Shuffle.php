<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;
use Inggo\BndoBot\Shuffle\Scores;

class Shuffle extends BaseCommand
{
    use Scores;

    const SLEEP_TIME = 15;

    private $round = 0;

    public $gamefile;
    public $wordfile;

    public function __construct($command)
    {
        parent::__construct($command);

        $this->gamefile = '.shuffle-' . $this->command->chat_id;
        $this->wordfile = $this->gamefile . '-word';

        $this->scorefile = $this->gamefile . '-score';

        $this->run();
    }

    public function run()
    {
        $subcommand = strtolower($this->command->args[1]);
        if ($subcommand === 'stop' && file_exists($this->gamefile)) {
            return $this->endGame();
        } elseif ($subcommand === 'start' && !file_exists($this->gamefile)) {
            ini_set('max_execution_time', 0);
            $this->sendMessage('Shuffle game started.');
            $this->startRound(true);
            return $this->game();
        } elseif ($subcommand === 'stats' && file_exists($this->scorefile)) {
            $this->showGameScores();
        } elseif ($subcommand === 'top10' && file_exists($this->globalscorefile)) {
            $this->showTopTen();
        } elseif ($subcommand === 'mystats' && file_exists($this->globalscorefile)) {
            $this->showUserStats($this->command->from_id, $this->command->from);
        } elseif (!file_exists($this->gamefile)) {
            $this->sendMessage('Type `/shuffle start` to start a game');
        } elseif (file_exists($this->gamefile)) {
            $this->sendMessage('Game is currently running. Type `/shuffle stop` to stop the game');
        } else {
            /* Ignore? */
        }

    }

    protected function endGame()
    {
        $this->sendMessage('Game stopped. Type `/shuffle start` to start game.');
        $this->unlinkIfExists($this->gamefile);
        $this->unlinkIfExists($this->wordfile);
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

    protected function generateWord()
    {
        file_put_contents($this->wordfile, $this->getRandomWord());
        $this->sendMessage('Unscramble the following: `' . $this->showRandomWord() . '`');
        $this->setGameState('1');
        sleep(self::SLEEP_TIME);
    }

    protected function showHint($count)
    {
        if (!file_exists($this->wordfile)) {
            return $this->endRound(false);
        }

        $word = $this->getWord();

        $hint = '';

        for ($i = 0; $i < $count; $i++) {
            $hint .= $word[$i];
        }

        while ($i < strlen($word)) {
            $hint .= '*';
            $i++;
        }

        $this->sendMessage('Hint ' . $count . ': `' . $hint . '`');
        $this->setGameState($count + 1);
        sleep(self::SLEEP_TIME);
    }

    protected function getWord()
    {
        return file_get_contents($this->wordfile);
    }

    protected function getRandomWord()
    {
        $f_contents = file("dictionary.in");
        return trim($f_contents[rand(0, count($f_contents) - 1)]);
    }

    protected function showRandomWord()
    {
        $word = file_get_contents($this->wordfile);
        return str_shuffle($word);
    }

    protected function startRound($force = false)
    {
        if ($this->round > 0 && $this->round % 5 === 0) {
            $this->showGameScore();
        }

        $this->round++;
        $this->sendMessage('Next word will appear in 15 seconds');
        $this->setGameState('0', $force);
        sleep(self::SLEEP_TIME);
    }

    protected function endRound($times_up = true)
    {
        if ($times_up) {
            $this->sendMessage('Times up! Answer is: `' . $this->getWord() . '`');
        }
        $this->setGameState('5');
        $this->unlinkIfExists($this->wordfile);
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
                $this->generateWord();
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
