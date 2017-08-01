<?php

define('RNM_BASE_URL', 'https://rate.nyo.me');

$url = RNM_BASE_URL . '/random';

$tag = $arg;

if ($tag) {
    $url .= '/' . $tag;
}

$url .= '.json';

$response = file_get_contents($url);
$json_response = json_decode($response);

$img = $json_response->content->upload->links->original;
$response_img = RNM_BASE_URL . $img;

file_put_contents('test.in', $response_img, FILE_APPEND);
