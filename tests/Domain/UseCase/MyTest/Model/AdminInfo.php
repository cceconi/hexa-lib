<?php declare(strict_types=1);
    
namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model;

use Apido\HexaLib\Model\Type\ValueObjectInterface;

class AdminInfo implements ValueObjectInterface
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->validate();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function validate(): void
    {
        if (empty($this->value)) {
            throw new \InvalidArgumentException('AdminInfo cannot be empty.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
