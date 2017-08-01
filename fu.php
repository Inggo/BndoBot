<?php

$subargs = array_shift($args);
$target = trim(implode(' ', $args));

if (!$target) {
    die();
}

$messages = [
    "🖕 🖕 🖕 %target% 🖕 🖕 🖕",
    "Putang ina mo %target%",
    "Pakyu ka %target%",
];

shuffle($messages);

$search = [
    '%target%',
    '%from',
];
$replace = [
    $target,
    $from,
];
$response_msg = str_replace($search, $replace, $messages[0]);
include 'respond.php';
