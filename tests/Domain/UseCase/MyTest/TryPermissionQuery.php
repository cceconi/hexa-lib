<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryPermissionQueryInterface;
use Apido\Tests\HexaLib\Domain\Spi\MyServiceInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryPermissionUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCaseResult;
use Psr\Log\LoggerInterface;

class TryPermissionQuery extends AbstractUseCase implements UseCaseInterface, TryPermissionQueryInterface
{
    private MyServiceInterface $myService;

    public function __construct(
        MyServiceInterface $myService,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->myService = $myService;
    }
    
    public function apply(TryPermissionUseCaseEvent $event): void
    {
        $this->handle($event, function (TryPermissionUseCaseEvent $event): TryMyUseCaseResult {
            $event->hasPermission(function (): bool {
                return true;
            });
            $this->eventHandler->debug($event, "😅 Debug message");
            return new TryMyUseCaseResult(new MyModel("🎉 Success!!!", "🔒 For Admin only", $this->myService->doSomething()));
        });
    }
}
