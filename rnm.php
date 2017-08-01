<?php

define('RNM_BASE_URL', 'https://rate.nyo.me');

$url = RNM_BASE_URL . '/random';

$tag = $arg;

if ($tag) {
    $url .= '/' . $tag;
}

$url .= '.json';

$response = file_get_contents($url);
file_put_contents('test.in', json_encode($response));
