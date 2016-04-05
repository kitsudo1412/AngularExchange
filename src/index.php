<?php
define('AE', '1');
session_start();

/*
 * Main index file
 */

// include functions
require_once 'includes/functions.php';

// print front-end
include 'frontend/header.php';
include 'frontend/container.php';
include 'frontend/footer.php';