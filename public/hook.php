<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

const HDR_GITHUB_SIGNATURE = "HTTP_X_HUB_SIGNATURE";
const HDR_GITHUB_EVENT = "HTTP_X_GITHUB_EVENT";
const HDR_GITHUB_DELIVERY = "HTTP_X_GITHUB_DELIVERY";
const LOGFILE = __DIR__ . '/../log/hook.log';

function logLine($format, ...$params)
{
    $line = vsprintf($format, $params);
    file_put_contents(LOGFILE, '['.date('Y-m-d H:i:s').'] '.$line.PHP_EOL, FILE_APPEND);
}

function logJson($message, $json)
{
    if(!is_string($json)) {
        $json = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }
    logLine($message);
    logLine($json);
}

$logDir = dirname(LOGFILE);
if(!is_dir($logDir)) {
    mkdir($logDir, 0770, true);
}
if(!is_file(LOGFILE)) {
    if(!touch(LOGFILE)) {
        throw new RuntimeException('Failed to create logfile: '.LOGFILE);
    }
}

$secret = trim(getenv('GITHUB_SECRET'));
if(strlen($secret) != 64) {
    throw new RuntimeException('Invalid env variable: GITHUB_SECRET');
}

if(!array_key_exists(HDR_GITHUB_EVENT, $_SERVER)) {
    logLine('Missing request header: X-GitHub-Event');
    die;
}
$eventType = $_SERVER[HDR_GITHUB_EVENT];
list($hmacType, $hmacValue) = explode("=", $_SERVER[HDR_GITHUB_SIGNATURE],2);
$body = file_get_contents("php://input");
$hmacCalc = hash_hmac($hmacType, $body, $secret);

if($hmacValue !== $hmacCalc) {
    throw new RuntimeException('Failed to verify HMAC security check');
}
$data = json_decode($body, false, 1024);

if($data->repository->full_name == 'ketfarku/hoax-radar') {
    chdir(__DIR__ . '/../');
    `git fetch --all && git reset --hard origin/master`;
    logLine('Updated from repository: %s', $data->repository->full_name);
}
else {
    logLine('Ignored repository: '.$data->repository->full_name);
}