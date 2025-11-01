<?php declare(strict_types=1);

namespace Apido\HexaLib\Model;

use Apido\HexaLib\Model\Type\EntityInterface;

abstract class AbstractEntity implements EntityInterface
{
    protected AbstractUid $uid;

    public function __construct(AbstractUid $uid)
    {
        $this->uid = $uid;
    }

    public function getUidValue(): string
    {
        return $this->uid->getValue();
    }

    public function getUid(): AbstractUid
    {
        return $this->uid;
    }

    public function __toString(): string
    {
        return (string) $this->uid;
    }

    public function equals(AbstractEntity $other): bool
    {
        return $this->uid->equals($other->getUid());
    }
}