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

        $response = file_get_contents(self::HS_API .
            rawurlencode($this->search_params), false, $context);
        $json_response = json_decode($response);

        if (!$json_response || empty($json_response)) {
            return $this->sendMessage('No Hearthstone card found for `' . $this->search_params . '`', true);
        }

        for ($i = 0; $i < count($json_response); $i++) {
            if ($json_response[$i]->img) {
                $response_img = $json_response[$i]->img;
                $this->respondWithPhoto($response_img, true);
                return;
            }
        }

        if ($i >= count($json_response)) {
            $this->respond('No Hearthstone card found for `' . $this->search_params . '`', true);
        }
    }
}
