<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;
use unreal4u\TelegramAPI\Telegram\Types\Custom\InputFile;

class WolframAlpha extends BaseCommand
{
    const API_URL = 'https://api.wolframalpha.com/v1/result?appid=' .
        WOLFRAM_APP . '&i=';

    public function __construct($command)
    {
        parent::__construct($command);

        $this->query = rawurlencode(trim(implode(' ', $this->command->params)));

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

        return $this->sendMessage('`' . $response . '`', true);
    }
}
