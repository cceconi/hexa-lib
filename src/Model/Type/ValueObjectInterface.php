<?php

namespace Apido\HexaLib\Model\Type;

interface ValueObjectInterface
{
    public function validate(): void;
    public function __toString(): string;
}
