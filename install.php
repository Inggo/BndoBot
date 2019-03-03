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

$info = $tgLog->performApiRequest(new GetWebhookInfo());

$last_error = new DateTime($info->last_error_date);

print "\tURL: {$info->url}\n";
print "\tMax connections: {$info->max_connections}\n";
print "\tPending update count: {$info->pending_update_count}\n";
print "\tLast error: \"{$info->last_error_message}\" @ {$last_error->format('d-m-Y H:i:s')}\n";

if (php_sapi_name() !== 'cli') {
    echo "</pre>";
}