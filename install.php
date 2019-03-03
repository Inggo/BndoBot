<?php

include 'core.php';

use \unreal4u\TelegramAPI\TgLog;
use \unreal4u\TelegramAPI\Telegram\Methods\SetWebhook;
use \unreal4u\TelegramAPI\Telegram\Methods\GetWebhookInfo;

$setWebhook = new SetWebhook();
$setWebhook->url = SITE_ROOT . BOT_TOKEN . '/hook.php';

if (php_sapi_name() !== 'cli') {
    echo "<pre>";
}

print "Setting webhook...\n";

$tgLog = new TgLog(BOT_TOKEN);
$tgLog->performApiRequest($setWebhook);

print "Getting webhook info...\n";

print $tgLog->performApiRequest(new GetWebhookInfo());

if (php_sapi_name() !== 'cli') {
    echo "</pre>";
}