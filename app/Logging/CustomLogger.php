<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CustomLogger
{
    protected $logger;

    public function __construct($channel = 'custom')
    {
        $this->logger = new Logger($channel);
        $logFilePath = storage_path('logs/custom.log');
        $this->logger->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));
    }

    public function log($level, $message, array $context = [])
    {
        $this->logger->log($level, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->log(Logger::INFO, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log(Logger::ERROR, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->log(Logger::DEBUG, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log(Logger::WARNING, $message, $context);
    }

    public function emergency($message, array $context = [])
    {
        $this->log(Logger::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->log(Logger::ALERT, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->log(Logger::CRITICAL, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->log(Logger::NOTICE, $message, $context);
    }

    public function logException($exception)
    {
        $this->error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
