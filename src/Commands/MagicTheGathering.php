<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class MagicTheGathering extends BaseCommand
{
    const MTG_API = 'https://api.magicthegathering.io/v1/cards?name=';

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
        $response = file_get_contents(self::MTG_API . rawurlencode($this->search_params));

        $json_response = json_decode($response);

        if (!$json_response || empty($json_response) || empty($json_response->cards)) {
            return $this->sendMessage('No Magic: The Gathering card found for `' . $this->search_params . '`');
        }

        for ($i = 0; $i < count($json_response->cards); $i++) {
            if ($json_response[$i]->imageUrl) {
                $response_img = $json_response[$i]->imageUrl;
                $this->respondWithPhoto($response_img);
                return;
            }
        }

        if ($i >= count($json_response)) {
            $this->respond('No Magic: The Gathering card found for `' . $this->search_params . '`');
        }
    }
}
