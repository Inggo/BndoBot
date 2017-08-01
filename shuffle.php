<?php

define('SHUFFLE_CHAT_ID', $chat_id);

use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

function sendMessage($msg)
{
    $tgLog = new TgLog(BOT_TOKEN, $logger);
    $sendMessage = new SendMessage();
    $sendMessage->chat_id = SHUFFLE_CHAT_ID;
    $sendMessage->parse_mode = 'Markdown';
    $sendMessage->text = $msg;
    return $tgLog->performApiRequest($sendMessage);
}

function game($game)
{
    if (!file_exists($game)) {
        return;
    }

    // Check game state
    $game_state = file_get_contents($game);

    switch ($game_state) {
        case '0':
            // Show a new word
            file_put_contents($game . '-word', getRandomWord());
            sendMessage('Unscramble the following: `' . showRandomWord($game) . '`');
            file_put_contents($game, '1');
            sleep(10);
            break;
        case '1':
            // Show hint (1 letter)
            sendMessage('Hint 1: `' . showHint($game, 1) . '`');
            file_put_contents($game, '2');
            sleep(10);
            break;
        case '2':
            sendMessage('Hint 2: `' . showHint($game, 2) . '`');
            // Show hint (2 letters)
            file_put_contents($game, '3');
            sleep(10);
            break;
        case '3':
            sendMessage('Hint 3: `' . showHint($game, 3) . '`');
            // Show hint (3 letters)
            file_put_contents($game, '4');
            sleep(10);
            break;
        case '4':
            sendMessage('Times up! Answer is: `' . showAnswer($game) . '`');
            // Times up! Show answer and restart game
            file_put_contents($game, '5');
            unlink($game . '-word');
            sleep(5);
            break;
        default:
            sendMessage('Next word will appear in 10 seconds');
            file_put_contents($game, '0');
            sleep(10);
            break;
    }

    set_time_limit(20);
    return game($game);
}

function showAnswer($game)
{
    return file_get_contents($game . '-word');
}

function showRandomWord($game)
{
    $word = file_get_contents($game . '-word');
    return str_shuffle($word);
}

function showHint($game, $count)
{
    $word = file_get_contents($game . '-word');

    $hint = '';

    for ($i = 0; $i < $count; $i++) {
        $hint .= $word[$i];
    }

    while ($i < strlen($word)) {
        $hint .= '*';
        $i++;
    }

    return $hint;
}

function getRandomWord()
{
    $f_contents = file("dictionary.in");
    return trim($f_contents[rand(0, count($f_contents) - 1)]);
}

$game = '.shuffle-' . $chat_id;

// Check if game is currently running
if (file_exists($game)) {
    // Delete the file stop game
    sendMessage('Game stopped. Type /shuffle again to start game.');
    unlink($game);
    unlink($game . '-word');
    die();
}

// Start the game
file_put_contents($game, '0');

sendMessage('Shuffle game started. First word will appear in 15 seconds.');

sleep(15);

game($game);
