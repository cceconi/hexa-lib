<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryMyCommandInterface;
use Apido\Tests\HexaLib\Domain\Api\TryMyComplexCommandInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyComplexUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyUseCaseEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyComplexUseCaseResult;
use Psr\Log\LoggerInterface;

class TryMyComplexCommand extends AbstractUseCase implements UseCaseInterface, TryMyComplexCommandInterface
{
    private TryMyCommandInterface $tryMyUseCase;

    public function __construct(
        TryMyCommandInterface $tryMyUseCase,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->tryMyUseCase = $tryMyUseCase;
    }
    
    public function apply(TryMyComplexUseCaseEvent $event): void
    {
        $this->handle($event, function (TryMyComplexUseCaseEvent $event): TryMyComplexUseCaseResult {
            $event->hasPermission();
            $myModel = $this->tryMyUseCase($event);
            // do something with MyModel object
            $myModel->setValue("🎉 Complex success!!!");
            return new TryMyComplexUseCaseResult($myModel);
        });
    }

    private function tryMyUseCase(TryMyComplexUseCaseEvent $mainEvent): MyModel
    {
        $event = TryMyUseCaseEvent::fromMainEvent($mainEvent, $mainEvent->getPayload()->getTryMyUseCasePayload());
        $this->tryMyUseCase->apply($event);
        return $event->getResult()->getData();
    }
}
