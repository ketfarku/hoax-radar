<?php

require_once __DIR__ . '/../bootstrap.php';

$blacklist = new \MkkpHoaxRadar\Blacklist($config);
$blacklist->getBlacklist();

echo 'Done!', PHP_EOL;