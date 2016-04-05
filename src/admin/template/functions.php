<?php
function print_menu_selected($page)
{
    if (!isset($_GET['view']))
        $_GET['view'] = "";

    if ($_GET['view'] == $page || (is_array($page) && in_array($_GET['view'], $page)))
        echo ' class="active"';
}