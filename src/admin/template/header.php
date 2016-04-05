<?php
defined('AE') or die('Access is denied'); ?>
<html xmlns:ns1="og" lang="en" xmlns="http://www.w3.org/1999/xhtml" ns1:xmlns="http://ogp.me/ns#" class=" jsEnabled"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
    <title>Admin Page - Angular Exchange</title>
    <meta http-equiv="X-UA-Compatible" content="IE=8"><link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/admin/template/admin-template.css">
</head>
<body>
<div id="header" class="std secondary">
    <h1><a href="<?php echo admin_url(); ?>"><img border="0" src="<?php echo site_url(); ?>/admin/template/images/paypal_logo.png" alt="PayPal"></a></h1>
    <div role="navigation" id="navPrimary">
        <ul class="secondary">
            <li<?php print_menu_selected(array('', 'topics', 'categories', 'posts', 'post_edit', 'category_edit', 'category_add')); ?>><a href="<?php echo admin_url(); ?>">Forums Management</a>
                <ul>
                    <li<?php print_menu_selected(array('', 'topics', 'posts', 'post_edit')); ?>><a href="<?php echo admin_url(); ?>">Topics</a></li>
                    <li<?php print_menu_selected(array('categories', 'category_edit', 'category_add')); ?>><a href="<?php echo admin_url(); ?>?view=categories">Categories</a></li>
                </ul>
            </li>
            <li<?php print_menu_selected(array('users', 'user_detail')); ?>><a href="<?php echo admin_url(); ?>?view=users">Users</a></li>
        </ul>
    </div>
</div>