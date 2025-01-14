<?php

namespace Apido\HexaLib\Message;

final class IdentityFilter implements FilterInterface
{
    public function filter(array $data): array
    {
        return $data;
    }
}