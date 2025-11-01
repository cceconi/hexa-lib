<?php declare(strict_types=1);

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Model;

use Apido\HexaLib\Model\Type\ValueObjectInterface;

class Status implements ValueObjectInterface
{
    public const SUCCESS = '🎉 Success!!!';
    public const ERROR = '⚠️ Exception thrown!';
    public const COMPLEX = '🎉 Complex success!!!';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->validate();
    }

    public function validate(): void
    {
        $validStatuses = [Status::SUCCESS, Status::ERROR, Status::COMPLEX];
        if (!in_array($this->value, $validStatuses, true)) {
            throw new \InvalidArgumentException('Invalid status value: ' . $this);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}