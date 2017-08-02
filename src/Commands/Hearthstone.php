<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class Hearthstone extends BaseCommand
{
    const HS_API = 'https://omgvamp-hearthstone-v1.p.mashape.com/cards/search/';

    public $search_params;

    public function __construct($command)
    {
        parent::__construct($command);

        $this->search_params = trim(implode(' ', $this->command->params));

        if (!$this->search_params) {
            return;
        }

        $this->run();
    }

    public function run()
    {
        // Create a stream
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "X-Mashape-Key: " . MASHAPE_KEY . "\r\n"
            ]
        ];

        $context = stream_context_create($opts);

        $command = array_shift($this->command);
        $response = file_get_contents(self::HS_API . rawurlencode($this->search_params), false, $context);
        $json_response = json_decode($response);

        if (!$json_response || empty($json_response)) {
            return;
        }

        $response_img = $json_response[0]->img;
        $this->respondWithPhoto($response_img);
    }
}