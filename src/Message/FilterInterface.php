<?php

namespace Apido\HexaLib\Message;

interface FilterInterface
{
    public function filter(array $data): array;
}
