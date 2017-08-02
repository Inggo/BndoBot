<?php

namespace Inggo\BndoBot\Shuffle;

// I'll fix this later
class Score
{
    public $score;
    public $name;
    public $id;

    public function __construct($id, $name, $score)
    {
        $this->id = $id;
        $this->name = $name;
        $this->score = $score;
    }
}

trait Scores
{
    public $gamefile;
    public $answerfile;

    public $scorefile;
    public $monthlyscorefile;
    public $globalscorefile;

    public $gamescores = [];
    public $monthlyscores = [];
    public $globalscores = [];

    // Put this in a separate class/trait in the future
    public function setupGameFiles($game)
    {
        $this->gamefile = '.' . $game . '-' . $this->command->chat_id;
        $this->answerfile = $this->gamefile . '-answer';

        $this->scorefile = $this->gamefile . '-score';
        $this->monthlyscorefile = $this->gamefile . '-score-' . date(Ym);
        $this->globalscorefile = $this->gamefile . '-score-all';
    }

    public function unlinkIfExists($file)
    {
        return !file_exists($file) || unlink($file);
    }

    public function addScore($user_id, $user_name, $points = 0)
    {
        $this->setScores($this->scorefile, $this->getGameScores(), $user_id, $user_name, $points);
        $this->setScores($this->monthlyscorefile, $this->getMonthlyScores(), $user_id, $user_name, $points);
        $this->setScores($this->globalscorefile, $this->getGlobalScores(), $user_id, $user_name, $points);
    }

    private function setScores($scorefile, $scores, $user_id, $user_name, $points)
    {
        $i = 0;

        while ($i < count($scores)) {
            if ($scores[$i]->id == $user_id) {
                $scores[$i]->score += $points;
                break;
            }

            $i++;
        }

        if ($i >= count($scores)) {
            $scores[] = new Score($user_id, $user_name, $points);
        }

        file_put_contents($scorefile, json_encode($scores));
    }

    public function getGameScores()
    {
        if (!file_exists($this->scorefile)) {
            return [];
        }

        $scores = json_decode(file_get_contents($this->scorefile));

        $this->gamescores = $this->sortScores($scores);

        return $this->gamescores;
    }

    public function getMonthlyScores()
    {
        if (!file_exists($this->monthlyscorefile)) {
            return [];
        }

        $scores = json_decode(file_get_contents($this->monthlyscorefile));

        $this->monthlyscores = $this->sortScores($scores);

        return $this->monthlyscores;
    }

    public function getGlobalScores()
    {
        if (!file_exists($this->globalscorefile)) {
            return [];
        }

        $scores = json_decode(file_get_contents($this->globalscorefile));

        $this->globalscores = $this->sortScores($scores);

        return $this->globalscores;
    }

    public function showGameScores()
    {
        $scores = $this->getGameScores();

        if (count($scores) === 0) {
            return;
        }

        $msg = "Ranking for this game:\n";

        for ($i = 0; $i < count($scores); $i++) {
            $msg .= $i + 1 . '. *' . $scores[$i]->name . '* - ' .
                $scores[$i]->score . "\n";
        }

        $this->sendMessage($msg);
    }

    public function showMonthlyTopTen()
    {
        $scores = $this->getMonthlyScores();

        if (count($scores) === 0) {
            return;
        }

        $msg = "Top 10 for this month:\n";

        for ($i = 0; $i < 10 && $i < count($scores); $i++) {
            $msg .= $i + 1 . '. *' . $scores[$i]->name . '* - ' .
                $scores[$i]->score . "\n";
        }

        $this->sendMessage($msg);
    }

    public function showTopTen()
    {
        $this->showMonthlyTopTen();

        $scores = $this->getGlobalScores();

        if (count($scores) === 0) {
            return;
        }

        $msg = "Top 10 of all time:\n";

        for ($i = 0; $i < 10 && $i < count($scores); $i++) {
            $msg .= $i + 1 . '. *' . $scores[$i]->name . '* - ' .
                $scores[$i]->score . "\n";
        }

        $this->sendMessage($msg);
    }

    public function showUserStats($user_id, $user_name)
    {
        $gamescore = 0;
        $globalscore = 0;

        $gamescores = $this->getGameScores();

        if ($gamescores && count($gamescores) > 0) {
            foreach ($gamescores as $score) {
                if ($score->id == $user_id) {
                    $gamescore = $score->score;
                    break;
                }
            }
        }

        $globalscores = $this->getGlobalScores();

        foreach ($globalscores as $score) {
            if ($score->id == $user_id) {
                $globalscore = $score->score;
                break;
            }
        }

        if ($gamescore > 0) {
            return $this->sendMessage('*' . $user_name . '*\'s current score: ' .
                $gamescore);
        } elseif ($globalscore > 0) {
            return $this->sendMessage('*' . $user_name . '*\'s record: ' .
                $globalscore);
        } else {
            return $this->sendMessage('You have not scored yet, ' . $user_name);
        }
    }

    public function sendScore($score, $rank = 0)
    {
        if ($rank > 0) {
            return $this->sendMessage($rank . '. *' . $score->name . '* - ' . $score->score);
        }

        return $this->sendMessage('*' . $score->name . '* - ' . $score->score);
    }

    public function sortScores($scores)
    {
        $sortedscores = [];

        foreach ($scores as $score) {
            $sortedscores[] = new Score($score->id, $score->name, $score->score);
        }

        usort($sortedscores, function ($a, $b) {
            if ($a->score == $b->score) {
                return 0;
            }
            return $a->score < $b->score ? 1 : -1;
        });

        return $sortedscores;
    }
}
