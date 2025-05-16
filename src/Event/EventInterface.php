<?php

namespace Apido\HexaLib\Event;

use Apido\HexaLib\Message\FilterInterface;
use Apido\HexaLib\Message\ResultInterface;
use Apido\HexaLib\Operation\OperationWriterInterface;
use Apido\HexaLib\Presenter\PresenterInterface;
use Apido\HexaLib\User\DomainUserInterface;
use Apido\HexaLib\Utils\Uuidv4Interface;

interface EventInterface
{
    public function __toString(): string;
    public function getUuidv4(): Uuidv4Interface;
    public function getMessage(string $message): string;
    public function toArray(): array;
    public function getUser(): DomainUserInterface;
    public function getPayload();
    public function getResult();
    public function setResult(ResultInterface $result): void;
    public function updateStatus(string $status);
    public function executeOperations(): void;
    public function getFilter(): FilterInterface;
    public function setPresenter(PresenterInterface $presenter): void;
    public function presentData(): void;
    public function isMaster(): bool;
    public function getEventId(): string;
    public function getLocalEventId(): string;
    public function getOpsWriter(): ?OperationWriterInterface;
    public function getStatus(): string;
    public function getClassName(): string;
}