<?php

namespace Inggo\BndoBot;

use unreal4u\TelegramAPI\Telegram\Types\Update;
use Inggo\BndoBot\Command;

class Webhook
{
    public $update;

    public function __construct()
    {
        $this->update = new Update(json_decode(file_get_contents('php://input'), true));

        if ($this->update->update_id == file_get_contents('.lastupdate')) {
            die();
        }

        file_put_contents('.lastupdate', $this->update->update_id);

        $this->command = new Command($this->update);
    }
}
