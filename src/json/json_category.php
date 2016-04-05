<?php
$json_string = "";
$show_topics = isset($_GET['show_topics']) && $_GET['show_topics'] ? true : false;
$show_posts = isset($_GET['show_posts']) && $_GET['show_posts'] ? true : false;

if (isset($_GET['id'])) {
    if ($_GET['id'] != 0) {
        $queried_category = AE_Category::get_category($_GET['id']);
        $topics_in_category = $queried_category->get_topics($offset, $limit);
    } else {
        $queried_category = new AE_Category();
        $topics_in_category = AE_Topic::get_topics(0, $offset, $limit);
    }

    $queried_category = get_object_vars($queried_category);

    if ($show_topics) {
        $queried_category['topics'] = array();

        if (count($topics_in_category) > 0) {
            foreach ($topics_in_category as $topic_in_category) {
                $topic_array = get_object_vars($topic_in_category);

                if ($show_posts) {
                    $posts_in_topic = $topic_in_category->get_posts($offset, $limit);
                    $topic_array['posts'] = array();
                    foreach ($posts_in_topic as $post_in_topic)
                        $topic_array['posts'][] = get_object_vars($post_in_topic);
                }

                $queried_category['topics'][] = $topic_array;
            }
        }
    }

    $json_string = json_encode($queried_category, JSON_PRETTY_PRINT);
}
else
{
    $categoriesJSON = array();
    $categories = AE_Category::get_categories();
    foreach ($categories as $category)
    {
        $categoriesJSON['categories'][] = $category;
    }
    $json_string = json_encode($categoriesJSON, JSON_PRETTY_PRINT);
}
echo $json_string;