<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class StrawpollResults extends BaseCommand
{
    const URL = 'http://strawpoll.me/';
    const API = 'https://www.strawpoll.me/api/v2/polls/';

    public function __construct($command)
    {
        parent::__construct($command);

        $id = trim(implode(' ', $this->command->params));
        
        if (!$id || (int) $id != $id || $id <= 0) {
            $this->sendMessage('Usage: `/strawresults <poll_id>`' . "\n" .
                'Example: `/strawresults 1234`');
            return;
        }

        $this->poll_id = $id;

        $this->run();
    }

    public function run()
    {
        $response = file_get_contents(self::API . $this->poll_id, false, $context);

        $json_response = json_decode($response);

        if (!$response || !$json_response->id) {
            return $this->sendMessage("Cannot retrieve Strawpoll results for `{$this->poll_id}`.", true);
        }

        $poll = $json_response;

        $url = self::URL . $json_response->id;
        $msg = "[{$poll->title}]({$url})\n";

        foreach ($poll->options as $i => $option) {
            $msg .= "*{$option}*: {$poll->votes[$i]}\n";
        }

        $this->sendMessage($msg, true);
    }
}
