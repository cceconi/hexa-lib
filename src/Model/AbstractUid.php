<?php declare(strict_types=1);

namespace Apido\HexaLib\Model;

use Apido\HexaLib\Model\Type\ValueObjectInterface;

abstract class AbstractUid implements ValueObjectInterface
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->validate();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(AbstractUid $other): bool
    {
        return $this->value === $other->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}