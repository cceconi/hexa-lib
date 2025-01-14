<?php

namespace Apido\HexaLib\Message;

use Apido\HexaLib\Presenter\PresenterInterface;
use JsonSerializable;

abstract class AbstractResult implements ResultInterface, JsonSerializable
{
    protected PresenterInterface $transformer;
    protected FilterInterface $filter;

    public function setPresenter(PresenterInterface $transformer): void
    {
        $this->transformer = $transformer;
    }

    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    public function jsonSerialize(): array
    {
        return $this->normalizeData();
    }

    abstract protected function toArray(): array;

    public function normalizeData(): array
    {
        return $this->transformer->present($this->filter->filter($this->toArray()));
    }
}
