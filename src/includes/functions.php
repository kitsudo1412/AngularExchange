<?php
defined('AE') or die('Access is denied');

// include libraries
require_once 'class-meekrodb.2.3.php';
require_once 'php-hooks.php';
require_once 'class-post.php';
require_once 'class-user.php';
require_once 'class-category.php';
require_once 'class-topic.php';
require_once 'class-notification.php';
require_once 'class-plugin.php';

// include configuration file
require_once __DIR__ . '/../config.php';

// include main functions
require_once 'db.php';

// global variables
$user = ae_get_user();
if (empty($user)) {
    $new_token = generate_user_token(1);
    setcookie('__AE_TOKEN__', $new_token, time() + 24 * 3600, "/");
    $_COOKIE['__AE_TOKEN__'] = $new_token;
    $user = ae_get_user();
}

// Common functions
function add_action($hook_name, $function_name)
{
    global $hooks;
    $hooks->add_action($hook_name, $function_name);
}

function do_action($hook_name)
{
    global $hooks;
    $hooks->do_action($hook_name);
}

function home_url()
{
    global $config;
    return $config['site_url'];
}

function site_url()
{
    return home_url();
}

function admin_url()
{
    return home_url() . '/admin';
}

function get_css_directory_uri()
{
    return home_url() . '/includes/css';
}

function get_js_directory_uri()
{
    return home_url() . '/includes/js';
}

/*
 * Access token helpers
 * AE|UserID|SessionID|ExpireStamp
 */

function ae_encrypt($plain_text)
{
    global $config;
    $key = pack('H*', $config['site_encryption_key']);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

    $cipher_text = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plain_text, MCRYPT_MODE_CBC, $iv);
    $cipher_text = $iv . $cipher_text;

    return base64_encode($cipher_text);
}

function ae_decrypt($cipher_text_base64)
{
    global $config;
    $key = pack('H*', $config['site_encryption_key']);
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);

    $cipher_text_decoded = base64_decode($cipher_text_base64);
    $iv_decoded = substr($cipher_text_decoded, 0, $iv_size);
    $cipher_text_decoded = substr($cipher_text_decoded, $iv_size);
    if (strlen($cipher_text_decoded) < 32)
        return '';

    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $cipher_text_decoded, MCRYPT_MODE_CBC, $iv_decoded), "\0");
}

function generate_user_token($user_id, $session_id = "")
{
    global $config;
    if ($session_id == "")
        $session_id = session_id();
    $token_info = array('AE', $user_id, $session_id, time() + $config['token_timeout']);
    return ae_encrypt(join('|', $token_info));
}

function get_token_info()
{
    if (!isset($_GET['access_token'])) {
        if (!isset($_COOKIE['__AE_TOKEN__']))
            return null;
        else
            $user_token = $_COOKIE['__AE_TOKEN__'];
    } else
        $user_token = $_GET['access_token'];

    $info_string = ae_decrypt($user_token);
    $info_string = explode('|', $info_string);
    $token_info = array();
    if ($info_string[0] != "AE")
        return null;
    else {
        $token_info['user_id'] = $info_string[1];
        $token_info['session_id'] = $info_string[2];
        $token_info['token_expire'] = $info_string[3];
    }
    if ($token_info['token_expire'] - time() < 0)
        return null;

    return $token_info;
}

function ae_get_user()
{
    $user_info = get_token_info();
    if (!isset($user_info['user_id']))
        return null;

    $user = AE_User::get_user($user_info['user_id']);
    $user->set_session_id($user_info['session_id']);
    $user->set_token_expire($user_info['token_expire']);
    return $user;
}

function generate_slug($fragment)
{
    $translate_symbols = array(
        '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
        '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
        '#(ì|í|ị|ỉ|ĩ)#',
        '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
        '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
        '#(ỳ|ý|ỵ|ỷ|ỹ)#',
        '#(đ)#',
        '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
        '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
        '#(Ì|Í|Ị|Ỉ|Ĩ)#',
        '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
        '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
        '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
        '#(Đ)#',
        "/[^a-zA-Z0-9-_]/",
    );
    $replace = array(
        'a',
        'e',
        'i',
        'o',
        'u',
        'y',
        'd',
        'A',
        'E',
        'I',
        'O',
        'U',
        'Y',
        'D',
        '-',
    );
    $fragment = preg_replace($translate_symbols, $replace, $fragment);
    return strtolower(preg_replace('/(-)+/', '-', $fragment));
}

function get_notice()
{
    if (isset($_GET['notice']))
    {
        return '<p class="notice">'.$_GET['notice'].'</p>';
    }
}