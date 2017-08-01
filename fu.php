<?php

$subargs = array_shift($args);
$target = trim(implode(' ', $args));

if ($target) {
    $response_msg = "🖕 🖕 🖕 " . $target . " 🖕 🖕 🖕";
}

include 'respond.php';
