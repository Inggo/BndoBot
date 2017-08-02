<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class RNM extends BaseCommand
{
    const BASE_URL = 'https://rate.nyo.me';

    public function __construct($command)
    {
        parent::__construct($command);
        $this->run();
    }

    public function run()
    {
        $url = self::BASE_URL . '/random.json';
        $response = file_get_contents($url);
        $json_response = json_decode($response);

        $img = $json_response->content->upload->links->original;
        $response_img = self::BASE_URL . $img;

        $this->respondWithPhoto($response_img, true);
    }
}
