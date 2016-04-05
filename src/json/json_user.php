<?php
$json_string = json_encode($user, JSON_PRETTY_PRINT);

if (isset($_GET['id']))
{
    $queried_user = AE_User::get_user($_GET['id']);
    $json_string = json_encode($queried_user, JSON_PRETTY_PRINT);
}

echo $json_string;