<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryExceptionEvent;

interface TryExceptionUseCaseInterface
{
    public function apply(TryExceptionEvent $event): void;
}
