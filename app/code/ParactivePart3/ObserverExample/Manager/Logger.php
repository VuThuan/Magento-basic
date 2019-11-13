<?php

namespace ParactivePart3\ObserverExample\Manager;

use Psr\Log\LoggerInterface;

class Logger
{
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log(string $message)
    {
        $this->logger->notice($message);
    }
}