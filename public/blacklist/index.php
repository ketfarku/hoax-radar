<?php

global $config;

require_once __DIR__ . '/../../bootstrap.php';

header('Content-Type: application/json');

if(!is_file(\MkkpHoaxRadar\Blacklist::CACHE)) {
    echo '[]';
}

$fh = fopen(\MkkpHoaxRadar\Blacklist::CACHE, 'r');
flock($fh, LOCK_SH);
fpassthru($fh);
flock($fh, LOCK_UN);
fclose($fh);
