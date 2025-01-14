<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Event;

use Apido\HexaLib\Event\AbstractEvent;
use Apido\HexaLib\Event\EventInterface;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionPayload;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message\TryExceptionResult;

class TryForbiddenEvent extends AbstractEvent implements EventInterface
{
    public function getPayload(): TryExceptionPayload
    {
        return $this->payload;
    }

    public function getResult(): TryExceptionResult
    {
        return $this->result;
    }

}
