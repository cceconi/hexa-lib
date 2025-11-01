<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\PayloadInterface;

class TryExceptionPayload implements PayloadInterface
{
    private string $uid;

    public function __construct(string $uid)
    {
        $this->uid = $uid;
    }

    public function getUid(): string
    {
        return $this->uid;
    }
    
    public function __toString(): string
    {
        return $this->uid;
    }
}
