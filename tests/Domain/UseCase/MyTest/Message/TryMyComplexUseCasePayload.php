<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\PayloadInterface;

class TryMyComplexUseCasePayload implements PayloadInterface
{
    private TryMyUseCasePayload $tryMyUseCasePayload;

    public function __construct(TryMyUseCasePayload $tryMyUseCasePayload)
    {
        $this->tryMyUseCasePayload = $tryMyUseCasePayload;
    }
    
    public function __toString(): string
    {
        return "";
    }

    public function getTryMyUseCasePayload(): TryMyUseCasePayload
    {
        return $this->tryMyUseCasePayload;
    }
}