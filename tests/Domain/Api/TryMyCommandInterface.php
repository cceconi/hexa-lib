<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyUseCaseEvent;

interface TryMyCommandInterface
{
    public function apply(TryMyUseCaseEvent $event): void;
}
