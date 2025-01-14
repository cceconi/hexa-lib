<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryMyComplexUseCaseEvent;

interface TryMyComplexUseCaseInterface
{
    public function apply(TryMyComplexUseCaseEvent $event): void;
}
