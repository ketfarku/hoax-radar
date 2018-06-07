<?php

$config = require __DIR__ . '/../cfg/config.php';

$params = [
    'alert' => 'HOAX ALERT!',
    'image' => 'https://mkkp-hoax-radar.lazos.me/img/putin.gif',
    'auditor' => 'mkkp.hu',
];

foreach($params as $k=>$v) {
    if(array_key_exists($k, $_GET)) {
        $params[$k] = $_GET[$k];
    }
}

?>
<div id="mkkp-hoax-radar-overlay">
    <div id="mkkp-hoax-radar-text">
        <span><?= $params['alert'] ?></span>
    </div>
    <div id="mkkp-hoax-radar-dance">
        <img src="<?= $params['image'] ?>" />
    </div>
    <div id="mkkp-hoax-radar-link">
        Auditálta:
        <a href="http://<?= $params['auditor'] ?>" target="_blank"><?= $params['auditor'] ?></a>
        <br/>
        Ha valami kimaradt a listából, <a href="<?= $config['report_url'] ?>" target="_blank">itt tudod nekünk elküldeni</a>!
    </div>
    <div id="mkkp-hoax-radar-close" onclick="var element = document.getElementById('mkkp-hoax-radar-overlay'); element.outerHTML = '';">
        X
    </div>
</div>

<style>

    @keyframes mkkp_hoax_radar_blinker {
        50% {
            opacity: 0.0;
        }
    }

    #mkkp-hoax-radar-overlay,
    #mkkp-hoax-radar-overlay div {
        float: none;
    }
    #mkkp-hoax-radar-overlay {
        background: rgba(255, 255, 255, 0.7);
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        text-align: center;
        z-index: 2147483647;
    }

    #mkkp-hoax-radar-text {
        display: inline-block;
        padding: 24px;
        background: #fff;
    }
    #mkkp-hoax-radar-text span {
        animation: mkkp_hoax_radar_blinker 1s linear infinite;
        color: #C00;
        font-size: 42px;
        font-weight: bold;
    }

    #mkkp-hoax-radar-dance {
        position: absolute;
        bottom: -4px;
        left: 0;
        right: 0;
    }

    #mkkp-hoax-radar-link {
        position: absolute;
        right: 12px;
        bottom: 12px;
        padding: 2px 8px;
        text-align: right;
    }

    #mkkp-hoax-radar-close {
        position: absolute;
        top: 12px;
        right: 12px;
        color: #B00;
        cursor: pointer;
        font-weight: bold;
        font-size: 32px;
    }

</style>
