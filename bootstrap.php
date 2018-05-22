<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);

global $config;
$config = require __DIR__ . '/cfg/config.php';
require_once __DIR__ . '/vendor/autoload.php';
