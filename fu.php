<?php

$subargs = array_shift($args);
$target = trim(implode(' ', $args));

$messages = [
    "🖕 🖕 🖕 %target% 🖕 🖕 🖕",
    "Putang ina mo %target%",
    "Pakyu ka %target%",
]

shuffle($messages);

if ($target) {
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
}
