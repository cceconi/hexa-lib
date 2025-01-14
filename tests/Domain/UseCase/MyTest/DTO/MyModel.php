<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\DTO;

class MyModel
{
    private string $value;
    private string $adminValue;
    private string $someValue;

    public function __construct(string $value, string $adminValue, string $someValue)
    {
        $this->value = $value;
        $this->adminValue = $adminValue;
        $this->someValue = $someValue;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            "value" => $this->value,
            "adminValue" => $this->adminValue,
            "someValue" => $this->someValue,
        ];
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getSomeValue(): string
    {
        return $this->someValue;
    }
}
