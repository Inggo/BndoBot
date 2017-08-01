<?php

use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SetWebhook;

include 'core.php';

$setWebhook = new SetWebhook();
$setWebhook->url = 'https://inggo.xyz/bndo-bot/' . BOT_TOKEN . '/webhook/';

$tgLog = new TgLog(BOT_TOKEN);
$tgLog->performApiRequest($setWebhook);
