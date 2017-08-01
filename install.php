<?php

include 'core.php';

use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SetWebhook;

$setWebhook = new SetWebhook();
$setWebhook->url = 'https://inggo.xyz/bndo-bot/' . BOT_TOKEN . '/hook.php';

$tgLog = new TgLog(BOT_TOKEN);
$tgLog->performApiRequest($setWebhook);

// Create DB
if ($db = sqlite_open('bndobot', 0666, $sqliteerror)) {
    sqlite_query($db, 'CREATE TABLE updates (id INTEGER)');
} else {
    die($sqliteerror);
}
