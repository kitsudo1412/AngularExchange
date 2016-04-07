<?php
define('AE', '1');

require_once 'functions.php';

if (isset($_GET['access_token']))
    $access_token = $_GET['access_token'];
elseif (isset($_COOKIE['__AE_TOKEN__']))
    $access_token = $_COOKIE['__AE_TOKEN__'];
else
    die("An access token is required for this action!");

$user_info = get_token_info($access_token);

if(!$user_info) die("Access Token Invalid!");
header('Content-Type: application/json');
if (isset($_GET['type']))
{
    $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 20;

    switch($_GET['type'])
    {
        case 'user':
            include 'json_user.php';
            break;
        case 'post':
            include 'json_post.php';
            break;
        case 'category':
            include 'json_category.php';
            break;
        case 'topic':
            include 'json_topic.php';
            break;
        default:
            break;
    }
} elseif (isset($_POST['type']))
{
    // handle post requests
    switch($_POST['type'])
    {
        case 'post':
            include 'action_post_create.php';
            break;
        default:
            break;
    }
}