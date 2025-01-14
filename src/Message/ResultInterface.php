<?php

namespace Apido\HexaLib\Message;

use Apido\HexaLib\Presenter\PresenterInterface;

interface ResultInterface
{
    public function __toString(): string;

    public function setPresenter(PresenterInterface $transformer): void;

    public function setFilter(FilterInterface $filter): void;
    
    public function normalizeData(): array;
}