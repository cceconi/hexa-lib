<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryMyCommandInterface;
use Apido\Tests\HexaLib\Domain\Spi\MyServiceInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCaseResult;
use Psr\Log\LoggerInterface;

class TryMyCommand extends AbstractUseCase implements UseCaseInterface, TryMyCommandInterface
{
    private MyServiceInterface $myService;

    public function __construct(
        MyServiceInterface $myService,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->myService = $myService;
    }
    
    public function apply(TryMyUseCaseEvent $event): void
    {
        $this->onSuccess("onSuccess", function (TryMyUseCaseEvent $event) {
            $this->eventHandler->log($event, "😄 Success");
        }, true);

        $this->handle($event, function (TryMyUseCaseEvent $event): TryMyUseCaseResult {
            $event->hasPermission();
            $this->eventHandler->debug($event, "😅 Debug message", true);
            $this->eventHandler->log($event, "😄 Start");
            $this->eventHandler->log($event, "😄 Stop", true);
            return new TryMyUseCaseResult(new MyModel("🎉 Success!!!", "🔒 For Admin only", $this->myService->doSomething($event->getPayload()->getThrowException())));
        });
    }
}
