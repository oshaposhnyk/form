<?php

namespace app\src\helpers;

use core\Application;

class Logger
{
    private $logFile;

    public function __construct($logFile = 'main.log')
    {
        $this->logFile = Application::$ROOT_DIR . "/logs/$logFile";
    }

    public function log($status, $message): void
    {
        $logEntry = '[' . date('Y-m-d H:i:s') . '] ' . $status . ': ' . $message . "\n";

        if (!file_exists($this->logFile)) {
            touch($this->logFile);
        }

        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}