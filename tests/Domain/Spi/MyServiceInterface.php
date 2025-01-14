<?php

namespace Apido\Tests\HexaLib\Domain\Spi;

interface MyServiceInterface
{
    public function doSomething(bool $throwException = false): string;
}
