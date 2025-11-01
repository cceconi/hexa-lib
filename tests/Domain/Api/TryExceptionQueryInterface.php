<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryExceptionEvent;

interface TryExceptionQueryInterface
{
    public function apply(TryExceptionEvent $event): void;
}
