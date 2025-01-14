<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event;

use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\HexaLib\Role\AbstractRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\AdminRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\MachineRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\UserRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionPayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionResult;

class TryExceptionEvent extends AbstractEvent implements EventInterface
{
    public function getPayload(): TryExceptionPayload
    {
        return $this->payload;
    }

    public function getResult(): TryExceptionResult
    {
        return $this->result;
    }

    protected function getPermissions(): array
    {
        return [
            AdminRole::class => AbstractRole::COMPLEMENTARY,
            UserRole::class => AbstractRole::FORBIDDEN,
            MachineRole::class => AbstractRole::ALLOW
        ];
    }

}
