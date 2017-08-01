<?php

$subargs = array_shift($args);
$target = trim(implode(' ', $args));

$messages = [
    "🖕 🖕 🖕 %s 🖕 🖕 🖕",
    "Putang ina mo %s",
    "Pakyu ka %s",
]

shuffle($messages);

if ($target) {
    $response_msg = sprintf($messages[0], $target, $from);
    include 'respond.php';
}
