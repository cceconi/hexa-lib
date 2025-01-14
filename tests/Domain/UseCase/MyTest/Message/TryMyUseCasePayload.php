<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\PayloadInterface;

class TryMyUseCasePayload implements PayloadInterface
{
    private bool $throwException;

    public function __construct(bool $throwException = false)
    {
        $this->throwException = $throwException;
    }
    
    public function __toString(): string
    {
        return "";
    }

    public function getThrowException(): bool
    {
        return $this->throwException;
    }
}
