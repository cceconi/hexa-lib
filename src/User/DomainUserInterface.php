<?php

namespace Apido\HexaLib\User;

interface DomainUserInterface
{
    public function getFullname(): string;
    public function getUid(): string;
    public function getRoles(): array;
    public function getRoleNames(): string;
    public function __toString();
}