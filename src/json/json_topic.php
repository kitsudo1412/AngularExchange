<?php
$json_string = "";
$show_posts = isset($_GET['show_posts']) && $_GET['show_posts'] ? true : false;

if (isset($_GET['id'])) {
    $queried_topic = AE_Topic::get_topic($_GET['id']);
    $posts_in_topic = $queried_topic->get_posts($offset, $limit);

    $queried_topic = get_object_vars($queried_topic);

    if ($show_posts){
        $queried_topic['posts'] = array();

        if (count($posts_in_topic) > 0) {
            foreach ($posts_in_topic as $post_in_topic) {
                $queried_topic['posts'][] = get_object_vars($post_in_topic);
            }
        }
    }

    $json_string = json_encode($queried_topic, JSON_PRETTY_PRINT);
}
echo $json_string;