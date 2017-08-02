<?php

namespace Inggo\BndoBot\Trivia;

class Question
{
    public $question;
    public $answer;

    public function __construct($question)
    {
        $qargs = explode('`', $question);
        $this->question = array_shift($qargs);
        $this->answer = implode('`', $qargs);
    }
}
