<?php

namespace Apido\Tests\HexaLib\Infrastructure\Service;

use Apido\Tests\HexaLib\Domain\Spi\MyServiceInterface;

class MyService implements MyServiceInterface
{
    public function __construct()
    {
    }

    public function doSomething(bool $throwException = false): string
    {
        if ($throwException) {
            throw new \Exception('Service Exception');
        }
        return 'something';
    }
}
