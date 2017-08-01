<?php

$subargs = array_shift($args);
$target = implode(' ', $args);
file_put_contents('test.in', "FU target " . $target . "\n", FILE_APPEND);
file_put_contents('test.in', "FU from " . $from . "\n", FILE_APPEND);
file_put_contents('test.in', "FU from " . $from_full . "\n", FILE_APPEND);
