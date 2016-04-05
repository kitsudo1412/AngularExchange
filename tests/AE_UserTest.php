<?php
// include configuration file
require_once '../src/config.php';

// include libraries
require_once '../src/includes/class-meekrodb.2.3.php';
require_once '../src/includes/php-hooks.php';
require_once '../src/includes/class-post.php';

// include main functions
require_once '../src/includes/db.php';
require_once '../src/includes/functions.php';
require_once '../src/includes/class-post.php';
require_once '../src/includes/class-user.php';
/**
 * Created by PhpStorm.
 * User: anh.dao
 * Date: 3/23/2016
 * Time: 9:24 AM
 */
class AE_UserTest extends PHPUnit_Framework_TestCase
{
    function Test1()
    {
        $user = new AE_User(array(1, 2, 3));
        print_r($user);
    }
}
