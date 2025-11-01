<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryExceptionQueryInterface;
use Apido\Tests\HexaLib\Domain\Spi\MyServiceInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryExceptionEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionResult;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model\BusinessEntity;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model\Status;
use Psr\Log\LoggerInterface;

class TryExceptionQuery extends AbstractUseCase implements UseCaseInterface, TryExceptionQueryInterface
{
    private MyServiceInterface $myService;
    
    public function __construct(
        LoggerInterface $logger,
        MyServiceInterface $myService
    ) {
        parent::__construct($logger);
        $this->myService = $myService;
    }
    
    public function apply(TryExceptionEvent $event): void
    {
        $this->onError("onError", function (TryExceptionEvent $event) {
            $this->eventHandler->log($event, "😱 Error");
        }, true);

        $this->handle($event, function (TryExceptionEvent $event): TryExceptionResult {
            $event->hasPermission(function () {
                return true;
            });
            $businessEntity = new BusinessEntity(
                $event->getPayload()->getUid(),
                Status::ERROR,
                "🔒 For Admin only",
                $this->myService->doSomething()
            );
            return new TryExceptionResult($businessEntity);
        });
    }
}
