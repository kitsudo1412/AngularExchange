<?php
defined('AE') or die('Access is denied');

/*
 * Configuration file
 */

// General configs
$config['site_url'] = 'http://localhost:8080/AngularExchange/src'; // without the ending slash
$config['site_encryption_key'] = "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3";
$config['token_timeout'] = 86400; // 1 day timeout

// DB configs
$config['dbName'] = 'aedb';
$config['dbUser'] = 'root';
$config['dbPassword'] = 'vertrigo';
$config['dbHost'] = 'localhost';
$config['dbPort'] = null;
$config['dbEncoding'] = 'utf8';