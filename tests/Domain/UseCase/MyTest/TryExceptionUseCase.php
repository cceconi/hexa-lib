<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest;

use Apido\HexaLib\UseCase\AbstractUseCase;
use Apido\HexaLib\UseCase\UseCaseInterface;
use Apido\Tests\HexaLib\Domain\Api\TryExceptionUseCaseInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryExceptionEvent;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionResult;
use Psr\Log\LoggerInterface;

class TryExceptionUseCase extends AbstractUseCase implements UseCaseInterface, TryExceptionUseCaseInterface
{
    public function __construct(
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
    }
    
    public function apply(TryExceptionEvent $event): void
    {
        $this->handle($event, function (TryExceptionEvent $event): TryExceptionResult {
            $event->hasPermission(function () {
                return true;
            });
            return new TryExceptionResult(new MyModel("⚠️ Exception thrown!", "🔒 For Admin only", "no value"));
        });
    }
}
