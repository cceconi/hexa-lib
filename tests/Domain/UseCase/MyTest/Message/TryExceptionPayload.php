<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\PayloadInterface;

class TryExceptionPayload implements PayloadInterface
{
    public function __toString(): string
    {
        return "";
    }
}
