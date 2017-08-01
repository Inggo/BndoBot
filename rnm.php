<?php

define('RNM_BASE_URL', 'https://rate.nyo.me');

$url = RNM_BASE_URL . '/random';

if (count($args) > 1) {
    $url .= '/' . $args[1];
}

$url .= '.json';

# file_put_contents('test.in', "Requesting " . $url . "\n", FILE_APPEND);

$response = file_get_contents($url);
$json_response = json_decode($response);

$img = $json_response->content->upload->links->original;
$response_img = RNM_BASE_URL . $img;

# file_put_contents('test.in', $response_img . "\n", FILE_APPEND);

include 'respond_photo.php';
