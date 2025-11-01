<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryPermissionUseCaseEvent;

interface TryPermissionQueryInterface
{
    public function apply(TryPermissionUseCaseEvent $event): void;
}
