<?php

require_once __DIR__ . '/../bootstrap.php';

$blacklist = new \MkkpHoaxRadar\Blacklist($config);
$blacklist->download();

echo 'Done!', PHP_EOL;