<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryForbiddenUseCaseInterface;
use Apido\Tests\HexaLib\Domain\Spi\MyServiceInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryForbiddenEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCaseResult;
use Psr\Log\LoggerInterface;

class TryForbiddenUseCase extends AbstractUseCase implements UseCaseInterface, TryForbiddenUseCaseInterface
{
    private MyServiceInterface $myService;

    public function __construct(
        MyServiceInterface $myService,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->myService = $myService;
    }
    
    public function apply(TryForbiddenEvent $event): void
    {
        $this->handle($event, function (TryForbiddenEvent $event): TryMyUseCaseResult {
            $event->hasPermission();
            $this->eventHandler->debug($event, "😅 Debug message");
            return new TryMyUseCaseResult(new MyModel("🎉 Success!!!", "🔒 For Admin only", $this->myService->doSomething()));
        });
    }
}