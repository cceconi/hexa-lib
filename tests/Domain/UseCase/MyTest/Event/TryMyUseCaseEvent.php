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

class TryMyUseCaseEvent extends AbstractEvent implements EventInterface
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
            UserRole::class => AbstractRole::ALLOW,
            AdminRole::class => AbstractRole::ALLOW,
            MachineRole::class => AbstractRole::COMPLEMENTARY
        ];
    }
}
