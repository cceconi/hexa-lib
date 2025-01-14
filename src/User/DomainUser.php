<?php

namespace Apido\HexaLib\User;

use Apido\HexaLib\Role\AbstractRole;

final class DomainUser implements DomainUserInterface
{
    private string $uid;
    /** @var AbstractRole[] */
    private array $roles;
    private string $fullname;

    public function __construct(string $uid, array $roles, string $fullname)
    {
        $this->uid = $uid;
        foreach ($roles as $role) {
            if (!$role instanceof AbstractRole) {
                throw new \InvalidArgumentException("Invalid role");
            }
        }
        $this->roles = $roles;
        $this->fullname = $fullname;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    /** @return AbstractRole[] */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getRoleNames(): string
    {
        return implode(", ", array_map(function (AbstractRole $role) {
            return $role->getRoleName();
        }, $this->roles));
    }

    public function __toString()
    {
        return $this->fullname . "|Roles: " . $this->getRoleNames();
    }
}