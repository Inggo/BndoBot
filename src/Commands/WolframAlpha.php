<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;
use unreal4u\TelegramAPI\Telegram\Types\Custom\InputFile;

use unreal4u\TelegramAPI\Exceptions\FileNotReadable;

class WolframAlpha extends BaseCommand
{
    const API_URL = 'https://api.wolframalpha.com/v1/simple?appid=' .
        WOLFRAM_APP . '&i=';

    public function __construct($command)
    {
        parent::__construct($command);

        $this->query = rawurlencode(trim(implode(' ', $this->command->params)));
        $this->tmpfile = '.wolfram-result-' . $this->command->id;

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

        file_put_contents($this->tmpfile, $response);

        try {
            $this->respondWithPhoto(new InputFile($this->tmpfile), true);
        } catch (FileNotReadable $e) {
            $this->sendMessage('Unable to attach image from Wolfram|Alpha. Try again later.', true);
        }

        unlink($this->tmpfile);
    }
}
