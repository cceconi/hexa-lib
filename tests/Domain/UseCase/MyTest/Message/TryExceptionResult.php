<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\AbstractResult;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO\MyModel;

class TryExceptionResult extends AbstractResult
{
    private MyModel $data;
    
    public function __construct(MyModel $data)
    {
        $this->data = $data;
    }
    
    public function __toString(): string
    {
        return "";
    }

    protected function toArray(): array
    {
        return $this->data->toArray();
    }

    public function getData(): MyModel
    {
        return $this->data;
    }
}
