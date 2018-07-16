<?php

global $config;
require_once __DIR__ . '/../bootstrap.php';

$blacklist = new \MkkpHoaxRadar\Blacklist($config);

?><!DOCTYPE html>
<html>
<head>
    <title>MKKP Hoax Radar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css" />
    <style>
        html {
            height: 100%;
            min-height: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            min-height: 100%;
        }
        #background {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('/img/mkkp-logo.png') no-repeat center;
            background-size: contain;
        }
        .container {
            padding: 20px 0;
        }
        .container > table.table {
            background: rgba(255,255,255,0.9);
        }
        .container > table.table tr,
        .container > table.table tr td,
        .container > table.table tr th {
            background: none;
        }
    </style>
</head>
<body>
<div id="background"></div>
<div class="container">
    <table class="table is-bordered is-narrow is-fullwidth">
        <thead>
        <tr>
            <td colspan="2" class="has-text-right">
                Ha valami kimaradt a listából, <a href="<?= $config['report_url'] ?>" target="_blank">itt tudod nekünk elküldeni</a>!
            </td>
        </tr>
        <tr>
            <th>Domain név (weboldal címe)</th>
            <th>Auditor (listához hozzáadta)</th>
        </tr>
        </thead>
    <?php foreach($blacklist->getBlacklist() as $item): ?>
    <tr>
        <td>
            <a href="http://<?= $item['domain'] ?>/" target="_blank"><?= $item['domain'] ?></a>
        </td>
        <td>
            <a href="http://<?= $item['auditor'] ?>/" target="_blank"><?= $item['auditor'] ?></a>
        </td>
    </tr>
    <?php endforeach ?>
        <tfoot>
        <tr>
            <td colspan="2" class="has-text-right">
                Ha valami kimaradt a listából, <a href="<?= $config['report_url'] ?>" target="_blank">itt tudod nekünk elküldeni</a>!
            </td>
        </tr>
        </tfoot>
    </table>
</div>
</body>
</html>
