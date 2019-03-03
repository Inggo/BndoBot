<?php

include 'core.php';

use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SetWebhook;

$setWebhook = new SetWebhook();
$setWebhook->url = SITE_ROOT . BOT_TOKEN . '/hook.php';

$tgLog = new TgLog(BOT_TOKEN);
$tgLog->performApiRequest($setWebhook);
