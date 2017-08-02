<?php

namespace Inggo\BndoBot\Shuffle;

// I'll fix this later
class Score
{
    public $score;
    public $name;
    public $id;
}

trait Scores
{
    public $scorefile;
    public $globalscorefile = '.shuffle-scores';

    public $gamescores = [];
    public $globalscores = [];

    public function addScore($user_id, $user_name, $points = 0)
    {
        $this->setScores($this->scorefile, $this->getGameScores(), $user_id, $user_name, $points);
        $this->setScores($this->globalscorefile, $this->getGlobalScores(), $user_id, $user_name, $points);
    }

    private function setScores($scorefile, $scores, $user_id, $user_name, $points)
    {
        for ($i = 0; $i < count($scores); $i++) {
            if ($scores[$i]->id === $user_id) {
                $scores[$i]->score += $points;
                break;
            }
        }

        if ($i > count($scores)) {
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

        $this->sendMessage('Ranking for this game:');

        for ($i = 0; $i < count($scores); $i++) {
            $this->sendScore($scores[$i], $i + 1);
        }
    }

    public function showTopTen()
    {
        $scores = $this->getGlobalScores();

        if (count($scores) === 0) {
            return;
        }

        $this->sendMessage('Top 10 of all time:');

        for ($i = 0; $i < 10 && $i < count($scores); $i++) {
            $this->sendScore($scores[$i], $i + 1);
        }
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
            return $a->score < $b->score ? -1 : 1;
        });

        return $sortedscores;
    }
}
