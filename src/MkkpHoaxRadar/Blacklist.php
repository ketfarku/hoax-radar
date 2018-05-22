<?php

namespace MkkpHoaxRadar;

use RuntimeException;
use Google_Client;
use Google_Service_Sheets;

class Blacklist
{
    const RANGE = "A2:D";
    const HEADER = ['domain', 'image', 'alert', 'auditor'];
    const CACHE = __DIR__ . '/../../cache/blacklist.json';

    protected $_config;
    protected $_client;
    protected $_sheets;

    public function __construct(array $config=[])
    {
        $cacheDir = dirname(self::CACHE);
        if(!is_dir($cacheDir)) {
            if(!mkdir($cacheDir, 0770, true)) {
                throw new RuntimeException('Failed to create cache directory for file '.self::CACHE);
            }
        }
        $this->_config = $config;
        if(!is_readable($config['credentials'])) {
            throw new RuntimeException('Invalid credentials file: '.$config['credentials']);
        }
        $this->_client = new Google_Client;
        $this->_client->setAuthConfigFile($this->getConfig('credentials'));
        $this->_client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $this->_client->setAccessType('offline');
        $this->_sheets = new Google_Service_Sheets($this->_client);
    }

    public function getConfig($key=null)
    {
        if(!$key) {
            return $this->_config;
        }
        if(!array_key_exists($key, $this->_config)) {
            return null;
        }
        return $this->_config[$key];
    }

    public function getClient()
    {
        return $this->_client;
    }

    public function getSheets()
    {
        return $this->_sheets;
    }

    public function getBlacklist()
    {
        if(!is_file(self::CACHE)) {
            $this->download();
        }
        return json_decode(file_get_contents(self::CACHE), true);
    }

    public function download()
    {
        $valueRange = $this->getSheets()->spreadsheets_values->get($this->getConfig('spreadsheet_id'), self::RANGE, ['majorDimension'=>'ROWS']);
        $list = [];
        foreach($valueRange['values'] as $row) {
            $list[] = array_combine(self::HEADER, $row);
        }
        file_put_contents(self::CACHE, json_encode($list));
    }
}