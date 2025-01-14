<?php

namespace Apido\Tests\HexaLib\Infrastructure\Utils;

use Apido\HexaLib\Utils\Uuidv4Interface;
use Ramsey\Uuid\Uuid;

class Uuidv4 implements Uuidv4Interface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
