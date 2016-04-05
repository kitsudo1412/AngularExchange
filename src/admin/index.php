<?php
define('AE', '1');
session_start();

require_once __DIR__ .'/../includes/functions.php';
require_once 'template/functions.php';

if ($user->role != 2)
{
    header("Location: ".$config['site_url']);
}

require_once "admin-actions.php";

$page = isset($_GET['view']) ? $_GET['view'] : '';
switch ($page)
{
    case 'categories':
        include 'template/categories.php';
        break;
    case 'users':
        include 'template/users.php';
        break;
    case 'topics':
        include 'template/topics.php';
        break;
    case 'posts':
        include 'template/posts.php';
        break;
    case 'user_detail':
        include 'template/user-detail.php';
        break;
    default:
        include 'template/topics.php';
        break;
}