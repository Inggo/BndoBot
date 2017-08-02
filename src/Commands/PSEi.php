<?php

namespace Inggo\BndoBot\Commands;

use Inggo\BndoBot\Commands\BaseCommand;

class PSEi extends BaseCommand
{
    const PSE_API = 'http://phisix-api.appspot.com/stocks/';

    public function __construct($command)
    {
        parent::__construct($command);

        $this->code = strtoupper($this->command->params[0]);

        if (!$this->code) {
            return;
        }

        $this->run();
    }

    public function run()
    {
        $response = file_get_contents(self::PSE_API . $this->code . '.json');
        $json_response = json_decode($response);

        if (!$json_response || !$json_response->stock[0]) {
             return $this->sendMessage('No PSEi Stock found for `' . $this->code . '` or API is down', true);
        }

        $as_of = $json_response->as_of;
        $stock = $json_response->stock[0];

        $msg = $stock->name . "\n<code style='color: %color%'>" . $stock->symbol . " " . $stock->price->amount;

        if ($stock->percent_change < 0) {
            $msg .= " ▼ ";
            $msg = str_replace('%color%', 'red', $msg);
        } elseif ($stock->percent_change > 0) {
            $msg .= " ▲ ";
            $msg = str_replace('%color%', 'green', $msg);
        } else {
            $msg .= " ● ";
            $msg = str_replace('%color%', 'gray', $msg);
        }

        $msg .= $stock->percent_change . "%</code>\n";
        $msg .= 'As of ' . $as_of;

        $this->sendHTML($msg, true);
    }
}
