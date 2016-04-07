<?php
defined('AE') or die('Access is denied');

if (isset($_POST['topic']) && isset($_POST['content'])) {
    $new_post = new AE_Post(array(
        'topic' => $_POST['topic'],
        'author' => $user_info['user_id'],
        'content' => htmLawed($_POST['content']),
        'ip' => $_SERVER['REMOTE_ADDR']
    ));

    $insert_id = $new_post->submit();

    $new_post = AE_Post::get_post($insert_id);

    echo json_encode($new_post, JSON_PRETTY_PRINT);
}