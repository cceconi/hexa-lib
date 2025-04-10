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
    /** @var Closure[] */
    protected array $successCallbacks = [];
    /** @var Closure[] */
    protected array $errorCallbacks = [];

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
            $this->executeSuccessCallbacks($event);
            $this->eventHandler->finish($event);
        } catch (PermissionException $pe) {
            $event->updateStatus(AbstractEvent::STATUS_DOMAIN_ERROR . " PermissionException " . $pe->getMessage());
            $this->executeErrorCallbacks($event, $pe);
            $this->eventHandler->error($event);
            throw $pe;
        } catch (DomainException $d) {
            $class = new ReflectionClass($d);
            $event->updateStatus(AbstractEvent::STATUS_DOMAIN_ERROR . " " . $class->getShortName() . " " . $d->getMessage());
            $this->executeErrorCallbacks($event, $d);
            $this->eventHandler->error($event);
            throw $d;
        } catch (Throwable $th) {
            $class = new ReflectionClass($th);
            $event->updateStatus(AbstractEvent::STATUS_INFRASTRUCTURE_ERROR . " " . $class->getShortName() . " " . $th->getMessage());
            $this->executeErrorCallbacks($event, $th);
            $this->eventHandler->critical($event);
            throw $th;
        }
    }

    protected function onSuccess(string $actionName, Closure $onSuccess, bool $alwaysCall = false): void
    {
        $this->successCallbacks[$actionName] = ["call" => $onSuccess, "alwaysCall" => $alwaysCall];
    }

    protected function onError(string $actionName, Closure $onError, bool $alwaysCall = false): void
    {
        $this->errorCallbacks[$actionName] = ["call" => $onError, "alwaysCall" => $alwaysCall];
    }

    private function executeSuccessCallbacks(EventInterface $event): void
    {
        foreach ($this->successCallbacks as $actionName => $callback) {
            try {
                if ($event->isMaster() || $callback["alwaysCall"]) {
                    $callback["call"]($event);
                }
            } catch (Throwable $e) {
                $this->eventHandler->log($event, "$actionName: " . $e->getMessage(), false, $e->getTrace());
            }
        }
    }

    private function executeErrorCallbacks(EventInterface $event, Throwable $e): void
    {
        foreach ($this->errorCallbacks as $actionName => $callback) {
            try {
                if ($event->isMaster() || $callback["alwaysCall"]) {
                    $callback["call"]($event, $e);
                }
            } catch (Throwable $e) {
                $this->eventHandler->log($event, "$actionName: " . $e->getMessage(), false, $e->getTrace());
            }
        }
    }
}
