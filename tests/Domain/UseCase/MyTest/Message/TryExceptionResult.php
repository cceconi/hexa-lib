<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Message;

use Apido\HexaLib\Message\AbstractResult;
use Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model\BusinessEntity;

class TryExceptionResult extends AbstractResult
{
    private BusinessEntity $data;
    
    public function __construct(BusinessEntity $data)
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

    public function getData(): BusinessEntity
    {
        return $this->data;
    }
}
