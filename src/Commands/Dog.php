<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class Dog extends BaseCommand
{
    const DOG_API = 'https://dog.ceo/api/breeds/image/random';

    public function __construct($command)
    {
        parent::__construct($command);
        $this->run();
    }

    public function run()
    {
        $response = file_get_contents(self::DOG_API);
        $json_response = json_decode($response);

        if ($json_response->status !== "success") {
            return;
        }

        $response_img = $json_response->message;

        $this->respondWithPhoto($response_img);
    }
}
