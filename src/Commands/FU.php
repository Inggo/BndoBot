<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class FU extends BaseCommand
{
    public $target;
    private $messages = [
        "🖕 🖕 🖕 %target% 🖕 🖕 🖕",
        "Putang ina mo %target%",
        "Pakyu ka %target%",
        "Fuck You %target%",
    ];

    public function __construct($command)
    {
        parent::__construct($command);

        array_shift($this->command->args);
        $this->target = trim(implode(' ', $this->command->args));

        if (!$this->target) {
            return;
        }

        $this->run();
    }

    public function run()
    {
        shuffle($this->messages);

        $search = [
            '%target%',
            '%from',
        ];

        $replace = [
            $this->target,
            $this->command->from,
        ];

        $response = str_replace($search, $replace, $this->messages[0]);

        $this->respond($response);
    }
}
