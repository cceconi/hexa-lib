<?php

namespace Apido\HexaLib\Handler;

use Apido\HexaLib\Event\EventInterface;
use Psr\Log\LoggerInterface;

final class EventHandler
{
    private static EventHandler $instance;
    private LoggerInterface $logger;
    private float $time;
    private array $times = [];
    
    private function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getInstance(LoggerInterface $logger): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($logger);
        }
        return self::$instance;
    }

    public function start(EventInterface $event): void
    {
        if ($event->isMaster()) {
            $this->time = microtime(true);
            $this->times = [];
        } else {
            $this->times[$event->getLocalAggregateId()] = microtime(true);
        }   
    }

    public function log(EventInterface $event, string $message, bool $withTime = false, array $context = []): void
    {
        $context = array_merge($context, $withTime ? $this->getExectime($event) : []);
        $this->logger->info($event->getMessage($message), $context);
    }

    public function debug(EventInterface $event, string $message, bool $withTime = false, array $context = []): void
    {
        $context = array_merge($context, $withTime ? $this->getExectime($event) : []);
        $this->logger->debug($event->getMessage($message), $context);
    }

    public function finish(EventInterface $event): void
    {
        $this->logger->info($event, $this->getExectime($event));
    }

    public function error(EventInterface $event): void
    {
        $this->logger->error($event, $this->getExectime($event));
    }

    public function critical(EventInterface $event): void
    {
        $this->logger->critical($event, $this->getExectime($event));
    }

    private function getExectime(EventInterface $event): array
    {
        return $event->isMaster() ? 
        [
            'exectime' => number_format((microtime(true) - $this->time) * 1000, 3) . 'ms',
        ] : [
            'exectime from master' => number_format((microtime(true) - $this->time) * 1000, 3) . 'ms',
            'exectime local' => number_format((microtime(true) - $this->times[$event->getLocalAggregateId()]) * 1000, 3) . 'ms'
        ];
    }
}