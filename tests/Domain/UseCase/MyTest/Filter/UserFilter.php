<?php

namespace Apido\Tests\HexaLib\Domain\UseCase\MyTest\Filter;

use Apido\HexaLib\Message\FilterInterface;

class UserFilter implements FilterInterface
{
    public function filter(array $myModel): array
    {
        unset($myModel["adminValue"]);
        return $myModel;
    }
}
