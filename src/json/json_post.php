<?php
$json_string = "";

if (isset($_GET['id'])) {
    $queried_post = AE_Post::get_post($_GET['id']);
    $json_string = json_encode($queried_post, JSON_PRETTY_PRINT);
}

echo $json_string;