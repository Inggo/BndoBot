<?php

define('DOG_API', 'https://dog.ceo/api/breeds/image/random');

$response = file_get_contents(DOG_API);
$json_response = json_decode($response);

if ($json_response->status !== "success") {
    die();
}

$response_img = $json_response->message;

include 'respond_photo.php';
