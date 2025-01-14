<?php

namespace Apido\HexaLib\UseCase;

use Closure;
use DomainException;
use Apido\HexaLib\Exception\PermissionException;
use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\HexaLib\Handler\EventHandler;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Throwable;

abstract class AbstractUseCase
{
    protected EventHandler $eventHandler;

    public function __construct(LoggerInterface $logger)
    {
        $this->eventHandler = EventHandler::getInstance($logger);
    }

    protected function handle(EventInterface $event, Closure $apply): void
    {
        try {
            $this->eventHandler->start($event);
            $event->setResult($apply($event));
            $event->executeOperations();
            $event->presentData();
            $event->updateStatus(AbstractEvent::STATUS_SUCCESS);
            $this->eventHandler->finish($event);
        } catch (PermissionException $pe) {
            $event->updateStatus(AbstractEvent::STATUS_DOMAIN_ERROR . " PermissionException " . $pe->getMessage());
            $this->eventHandler->error($event);
            throw $pe;
        } catch (DomainException $d) {
            $class = new ReflectionClass($d);
            $event->updateStatus(AbstractEvent::STATUS_DOMAIN_ERROR . " " . $class->getShortName() . " " . $d->getMessage());
            $this->eventHandler->error($event);
            throw $d;
        } catch (Throwable $th) {
            $class = new ReflectionClass($th);
            $event->updateStatus(AbstractEvent::STATUS_INFRASTRUCTURE_ERROR . " " . $class->getShortName() . " " . $th->getMessage());
            $this->eventHandler->critical($event);
            throw $th;
        }
    }
}
