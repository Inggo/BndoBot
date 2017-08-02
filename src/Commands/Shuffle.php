<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class Shuffle extends BaseCommand
{
    const SLEEP_TIME = 15;

    public function __construct($command)
    {
        parent::__construct($game);

        $this->gamefile = '.shuffle-' . $chat_id;
        $this->wordfile = $this->gamefile . '-word';

        $this->run();
    }

    public function run()
    {
        if (strtolower($this->command->args[1]) === 'stop') && file_exists($this->gamefile)) {
            return $this->endGame();
        }

        if (strtolower($this->command->args[1]) === 'start') && !file_exists($this->gamefile)) {
            $this->sendMessage('Shuffle game started.');
            $this->startRound();
            return $this->game();
        }
    }

    protected function endGame()
    {
        $this->sendMessage('Game stopped. Type `/shuffle start` to start game.');
        unlink($this->gamefile);
        unlink($this->wordfile);
    }

    protected function getGameState()
    {
        return file_get_contents($this->gamefile);
    }

    protected function setGameState($state)
    {
        file_put_contents($this->gamefile, $state);
    }

    protected function generateWord()
    {
        file_put_contents($this->wordfile, $this->getRandomWord());
        sendMessage('Unscramble the following: `' . $this->showRandomWord() . '`');
        $this->setGameState('1');
        sleep(self::SLEEP_TIME);
    }

    protected function showHint($count)
    {
        $word = $this->getWord();

        $hint = '';

        for ($i = 0; $i < $count; $i++) {
            $hint .= $word[$i];
        }

        while ($i < strlen($word)) {
            $hint .= '*';
            $i++;
        }

        sendMessage('Hint ' . $count . ': `' . $hint . '`');
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

    protected function startRound()
    {
        sendMessage('Next word will appear in 15 seconds');
        $this->setGameState('0');
    }

    protected function endRound()
    {
        sendMessage('Times up! Answer is: `' . $this->getWord() . '`');
        $this->setGameState('5');
        unlink($this->wordfile);
    }

    public function game()
    {
        if (!file_exists($this->gamefile)) {
            return;
        }

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

        set_time_limit(30);
        return $this->game();
    }
}
