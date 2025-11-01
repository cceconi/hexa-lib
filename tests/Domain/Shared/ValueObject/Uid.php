<?php declare(strict_types=1);

namespace Apido\Tests\HexaLib\Domain\Shared\ValueObject;

use Apido\HexaLib\Model\AbstractUid;

final class Uid extends AbstractUid
{
    public function validate(): void
    {
        if (!preg_match('/^[a-f0-9]{32}$/', $this->value)) {
            throw new \InvalidArgumentException('Invalid UID format.');
        }
    }
}