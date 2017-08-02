<?php

namespace Inggo\BndoBot;

use unreal4u\TelegramAPI\Telegram\Types\Update;
use Inggo\BndoBot\Command;

class Webhook
{
    public $update;

    public function __construct()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $this->update = new Update($request);
        $this->command = new Command($this->update);
    }
}
