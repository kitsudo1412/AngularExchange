<?php
defined('AE') or die('Access is denied');

/*
 * Common DB queries using MeekroDB 2.3
 * http://meekro.com/index.php
 */

DB::$user = $config['dbUser'];
DB::$password = $config['dbPassword'];
DB::$dbName = $config['dbName'];
DB::$encoding = 'utf8';
DB::$error_handler = false; // since we're catching errors, don't need error handler
DB::$throw_exception_on_error = true;

function db_home_url()
{
    $db_home_url = DB::queryFirstRow('SELECT `option_value` FROM `ae_options` WHERE `option_name` = %s', 'home_url');
    if ($db_home_url)
        return $db_home_url['option_value'];
    global $config;
    return $config['home_url'];
}

/* POST */
function db_post_get_by_id($post_id)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_posts` WHERE `id` = %d', $post_id);
    return $post_row;
}

function db_post_update_content($id, $new_content)
{
    DB::update('ae_posts', array(
        'content' => $new_content
    ), "id=%s", $id);
}

function db_post_like_increment($id)
{
    DB::query("UPDATE TABLE `ae_posts` SET `like_count` = `like_count` + 1 WHERE `id` = {$id}");
}

function db_post_add_like($id, $user)
{
    DB::insert('ae_likes', array(
        'post' => $id,
        'user' => $user
    ));
}

function db_post_like_minus($id)
{
    DB::query("UPDATE TABLE `ae_posts` SET `like_count` = `like_count` - 1 WHERE `id` = {$id}");
}

function db_post_remove_like($post, $user)
{
    DB::delete('ae_likes', "post=%s and user=%s", $post, $user);
}

function db_post_get_likes($post, $offset, $num)
{
    $users = array();

    $results = DB::query("SELECT `user` FROM `ae_likes` WHERE `post` = %d LIMIT %d OFFSET %d", $post, $num, $offset);

    foreach ($results as $result) {
        $users[] = $result['user'];
    }

    return $users;
}

function db_post_delete($post_id)
{
    DB::delete('ae_posts', "id=%d", $post_id);
}

/* USER */
function db_user_get_by_id($user_id)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_users` WHERE `id` = %d', $user_id);
    return $post_row;
}

function db_user_check_password($user_id, $plain_password)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_users` WHERE `id` = %d AND `pass` = MD5(%s)', $user_id, $plain_password);
    return $post_row;
}

function db_user_change_password($user_id, $new_plain_password)
{
    DB::update('ae_users', array(
        'pass' => md5($new_plain_password)
    ), "id=%s", $user_id);
}

function db_user_change_display_name($user_id, $new_display_name)
{
    DB::update('ae_users', array(
        'display_name' => $new_display_name
    ), "id=%s", $user_id);
}

function db_user_change_email_address($user_id, $new_email_address)
{
    DB::update('ae_users', array(
        'email' => $new_email_address
    ), "id=%s", $user_id);
}

function db_user_post_count_increment($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `post_count` = `post_count` + 1 WHERE `id` = {$user_id}");
}

function db_user_post_count_minus($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `post_count` = `post_count` - 1 WHERE `id` = {$user_id}");
}

function db_user_topic_count_increment($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `topic_count` = `topic_count` + 1 WHERE `id` = {$user_id}");
}

function db_user_topic_count_minus($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `topic_count` = `topic_count` - 1 WHERE `id` = {$user_id}");
}

function db_user_likes_given_increment($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `likes_given` = `likes_given` + 1 WHERE `id` = {$user_id}");
}

function db_user_likes_given_minus($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `likes_given` = `likes_given` - 1 WHERE `id` = {$user_id}");
}

function db_user_likes_received_increment($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `likes_received` = `likes_received` + 1 WHERE `id` = {$user_id}");
}

function db_user_likes_received_minus($user_id)
{
    DB::query("UPDATE TABLE `ae_users` SET `likes_received` = `likes_received` - 1 WHERE `id` = {$user_id}");
}

function db_user_get_posts($user_id, $offset = 0, $num = 10)
{
    return DB::query("SELECT * FROM `ae_posts` WHERE `author` = %d LIMIT %d OFFSET %d", $user_id, $num, $offset);
}

function db_user_get_topics($user_id, $offset = 0, $num = 10)
{
    return DB::query("SELECT * FROM `ae_topics` WHERE `original_poster` = %d LIMIT %d OFFSET %d", $user_id, $num, $offset);
}

function db_user_get_notifications($user_id, $offset =0, $num = 5)
{
    return DB::query("SELECT * FROM `ae_notifications` WHERE `user` = %d LIMIT %d OFFSET %d", $user_id, $num, $offset);
}

function db_user_add_notification($user_id, $is_new_reply, $topic_id, $is_read)
{
    DB::insert('ae_notifications', array(
        'user' => $user_id,
        'is_new_reply' => $is_new_reply,
        'topic' => $topic_id,
        'is_read' => $is_read
    ));
}

function db_user_get_users($offset = 0, $limit = 40)
{
    return DB::query("SELECT * FROM `ae_users` LIMIT %d OFFSET %d", $limit, $offset);
}

function db_user_delete($user_id)
{
    DB::delete('ae_users', "id=%d", $user_id);
}

function db_user_insert($user)
{
    $user=get_object_vars($user);
    try {
        $insert_id = DB::insert('ae_users',
            array(
                'login' => $user['login'],
                'pass' => md5($user['pass']),
                'display_name' => $user['display_name'],
                'role' => $user['role'],
                'email' => $user['email'],
            ));
    }
    catch (MeekroDBException $e) {
        $insert_id = '-1';
    }
    return $insert_id;
}

function db_user_update($user_id, $login, $pass, $display_name, $role, $email)
{
    DB::update('ae_users', array(
        'login' => $login,
        'pass' => md5($pass),
        'display_name' => $display_name,
        'role' => $role,
        'email' => $email
    ),'id=%d', $user_id);
}

/* CATEGORY */
function db_category_get_by_id($category_id)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_categories` WHERE `id` = %d', $category_id);
    return $post_row;
}

function db_category_change_name($category_id, $new_category_name)
{
    DB::update('ae_categories', array(
        'name' => $new_category_name
    ), "id=%s", $category_id);
}

function db_category_get_topics($category_id, $offset = 0, $num = 10)
{
    if ($category_id == 0)
        return DB::query("SELECT * FROM `ae_topics` LIMIT %d OFFSET %d", $num, $offset);
    return DB::query("SELECT * FROM `ae_topics` WHERE `category` = %d LIMIT %d OFFSET %d", $category_id, $num, $offset);
}

function db_category_get_categories()
{
    return DB::query("SELECT * FROM `ae_categories`");
}

function db_category_delete($category_id)
{
    DB::delete('ae_categories', "id=%d", $category_id);
}

function db_category_insert($category_name)
{
    $category_slug = generate_slug($category_name);
    $suffix_number = 0;

    while (true)
    {
        $suffix_number++;
        $hypothetical_row = DB::queryFirstField("SELECT `id` FROM `ae_categories` WHERE `slug` = %s", $category_slug);
        if ($hypothetical_row == null)
            break;
        else
            $category_slug = generate_slug($category_name).'-'.$suffix_number;
    }

    $random_color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

    return DB::insert('ae_categories', array(
        'slug' => $category_slug,
        'name' => $category_name,
        'color' => $random_color
    ));
}

function db_category_update_slug($category_id, $new_slug)
{
    $category_slug = generate_slug($new_slug);
    $suffix_number = 0;

    while (true)
    {
        $suffix_number++;
        $hypothetical_row = DB::queryFirstField("SELECT `id` FROM `ae_categories` WHERE `slug` = %s", $category_slug);

        if ($hypothetical_row == $category_id)
            return;

        if ($hypothetical_row == null)
            break;
        else
            $category_slug = generate_slug($new_slug).'-'.$suffix_number;
    }

    DB::update('ae_categories', array(
        'slug' => $category_slug
    ), "id=%s", $category_id);
}

function db_category_update_name($category_id, $new_name)
{
    DB::update('ae_categories', array(
        'name' => $new_name
    ), "id=%s", $category_id);
}

function db_category_update_color($category_id, $color_string)
{
    DB::update('ae_categories', array(
        'color' => $color_string
    ), "id=%d", $category_id);
}

/* NOTIFICATION */
function db_notification_get_by_id($notification_id)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_notifications` WHERE `id` = %d', $notification_id);
    return $post_row;
}

/* TOPIC */
function db_topic_get_by_id($topic_id)
{
    $post_row = DB::queryFirstRow('SELECT * FROM `ae_topics` WHERE `id` = %d', $topic_id);
    return $post_row;
}

function db_topic_get_posts($topic_id, $offset = 0, $num = 10)
{
    return DB::query("SELECT * FROM `ae_posts` WHERE `topic` = %d LIMIT %d OFFSET %d", $topic_id, $num, $offset);
}

function db_topic_change_title($topic_id, $new_topic_title)
{
    DB::update('ae_topics', array(
        'title' => $new_topic_title
    ), "id=%s", $topic_id);
}

function db_topic_view_count_increment($topic_id)
{
    DB::query("UPDATE TABLE `ae_topics` SET `view_count` = `view_count` + 1 WHERE `id` = %d", $topic_id);
}

function db_topic_comment_count_increment($topic_id)
{
    DB::query("UPDATE TABLE `ae_topics` SET `comment_count` = `comment_count` + 1 WHERE `id` = %d", $topic_id);
}

function db_topic_comment_count_minus($topic_id)
{
    DB::query("UPDATE TABLE `ae_topics` SET `comment_count` = `comment_count` - 1 WHERE `id` = %d", $topic_id);
}

function db_topic_delete($topic_id)
{
    DB::delete('ae_topics', "id=%d", $topic_id);
}