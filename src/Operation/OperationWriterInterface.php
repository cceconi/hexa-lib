<?php

namespace Apido\HexaLib\Operation;

interface OperationWriterInterface
{
    public function execute(string $aggregateId): void;
}