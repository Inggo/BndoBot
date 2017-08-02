<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class Strawpoll extends BaseCommand
{
    const URL = 'http://strawpoll.me/';
    const API = 'https://www.strawpoll.me/api/v2/polls';
    const HELPTXT = 'Format: `/strawpoll Poll Title|Option 1,Option 2,Option 3,...`';
    const EXAMPLE = 'Example: `/strawpoll Favorite Chicken Part?|Breast,Thighs,Legs,Wings`';
    const HELPMSG = self::HELPTXT . "\n" . self::EXAMPLE;

    public function __construct($command)
    {
        parent::__construct($command);

        $params = trim(implode(' ', $this->command->params));
        $params = explode('|', $params);
        $title = $params[0];
        $options = explode(',', $params[1]);

        if (!$title) {
            return $this->sendMessage("Please provide your Poll Title.\n" .
                self::HELPMSG, true);
        }

        if (!$options || empty($options) || count($options) < 2) {
            return $this->sendMessage("Please provide two or more options.\n" .
                self::HELPMSG, true);
        }

        $this->title = trim($title);
        $this->options = [];
        foreach ($options as $option) {
            $options[] = trim($option);
        }

        $this->run();
    }

    public function run()
    {
        $post_data = [
            "title" => $this->title,
            "options" => $this->options,
        ];

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => json_encode($post_data)
            )
        ));

        $response = file_get_contents(self::API, false, $context);

        $json_response = json_decode($response);

        if (!$response || !$json_response->id) {
            return $this->sendMessage('Cannot create Strawpoll. Try again later.', true);
        }

        $this->respond("Poll created (ID: `{$json_response->id}`)\n" .
            self::URL . $json_response->id, true);
    }
}
