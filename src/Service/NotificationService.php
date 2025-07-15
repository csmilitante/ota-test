<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class NotificationService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send()
    {
        $this->logger->info('Sending mail');
    }
}
