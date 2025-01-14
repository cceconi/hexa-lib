<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryForbiddenEvent;

interface TryForbiddenUseCaseInterface
{
    public function apply(TryForbiddenEvent $event): void;
}
