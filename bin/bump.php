<?php

require_once __DIR__ . '/../bootstrap.php';


$semver = 'patch';
if($argc > 1) {
    switch($argv[1]) {
        case 'major':
        case 'minor':
        case 'patch':
        $semver = $argv[1];
            break;
    }
}


$fileJson = __DIR__ . '/../composer.json';
$packageJson = json_decode(file_get_contents($fileJson));
$version = explode('.', $packageJson->version);
switch($semver) {
    case 'major':
        $version[0]++;
        $version[1] = 0;
        $version[2] = 0;
        break;
    case 'minor':
        $version[1]++;
        $version[2] = 0;
        break;
    case 'patch':
        $version[2]++;
        break;
}
$packageJson->version = $version = join('.', $version);
$tag = 'v' . $version;
file_put_contents($fileJson, json_encode($packageJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX);

const BROWSERS = ['chrome', 'mozilla'];

foreach(BROWSERS as $browser) {
    echo "Processing browser {$browser}", PHP_EOL;
    $zipPath = __DIR__ . "/../browser/dist/{$browser}-{$version}.zip";
    $dirPath = __DIR__ . "/../browser/{$browser}/";
    if(is_file($zipPath)) {
        unlink($zipPath);
    }
    $zip = new ZipArchive;
    if($zip->open($zipPath, ZipArchive::CREATE) === true) {
        foreach(glob($dirPath.'*') as $file) {
            $zip->addFile($file, basename($file));
        }
        if($zip->close()) {
            `git add {$file}`;
            echo "Done {$browser}", PHP_EOL;
        }
        else {
            throw new RuntimeException('Failed to close ZIP file for writing: '.$zipPath);
        }
    }
    else {
        throw new RuntimeException('Failed to create ZIP file for writing: '.$zipPath);
    }
}

`git commit -m "bump {$tag}" && git tag {$tag} && git push`;
