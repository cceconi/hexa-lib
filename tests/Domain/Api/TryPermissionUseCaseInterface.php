<?php

namespace Apido\Tests\HexaLib\Domain\Api;

use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event\TryPermissionUseCaseEvent;

interface TryPermissionUseCaseInterface
{
    public function apply(TryPermissionUseCaseEvent $event): void;
}
