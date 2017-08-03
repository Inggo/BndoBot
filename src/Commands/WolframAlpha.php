<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class WolframAlpha extends BaseCommand
{
    const API_URL = 'https://api.wolframalpha.com/v1/simple?appid=' .
        WOLFRAM_APP . '&i=';

    public function __construct($command)
    {
        parent::__construct($command);

        $this->query = rawurlencode(trim(implode(' ', $this->command->query)));

        if (!$this->query) {
            return;
        }

        $this->run();
    }

    public function run()
    {
        $url = self::API_URL . $this->query;
        $response = file_get_contents($url);

        if (!$response) {
            return $this->sendMessage('Unable to query Wolfram|Alpha. Try again later.', true);
        }

        $this->respondWithPhoto($response, true);
    }
}
