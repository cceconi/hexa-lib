<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event;

use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\HexaLib\Role\AbstractRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\AdminRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\MachineRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\UserRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCasePayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyUseCaseResult;

class TryPermissionUseCaseEvent extends AbstractEvent implements EventInterface
{
    public function getPayload(): TryMyUseCasePayload
    {
        return $this->payload;
    }

    public function getResult(): TryMyUseCaseResult
    {
        return $this->result;
    }

    protected function getPermissions(): array
    {
        return [
            AdminRole::class => AbstractRole::ALLOW,
            UserRole::class => AbstractRole::COMPLEMENTARY,
            MachineRole::class => "maybe"
        ];
    }
}
