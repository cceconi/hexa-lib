<?php

namespace Apido\HexaLib\Model\Type;

interface EntityInterface
{
    public function getUidValue(): string;
    public function validate(): void;
    public function __toString(): string;
}
