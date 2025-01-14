<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event;

use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\HexaLib\Message\IdentityFilter;
use Apido\HexaLib\Role\AbstractRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\AdminRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\MachineRole;
use Apido\Tests\HexaLib\Domain\Shared\Role\UserRole;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Filter\UserFilter;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyComplexUseCasePayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryMyComplexUseCaseResult;

class TryMyComplexUseCaseEvent extends AbstractEvent implements EventInterface
{
    public function getPayload(): TryMyComplexUseCasePayload
    {
        return $this->payload;
    }

    public function getResult(): TryMyComplexUseCaseResult
    {
        return $this->result;
    }

    protected function getPermissions(): array
    {
        return [
            AdminRole::class => AbstractRole::ALLOW,
            UserRole::class => AbstractRole::ALLOW,
            MachineRole::class => AbstractRole::ALLOW
        ];
    }

    protected function getFilters(): array
    {
        return [
            AdminRole::class => IdentityFilter::class,
            UserRole::class => UserFilter::class,
            MachineRole::class => IdentityFilter::class
        ];
    }
}
