<?php

namespace Apido\HexaLib\Role;

use Closure;
use Apido\HexaLib\Exception\MissingComplementaryClosureException;
use ReflectionClass;

abstract class AbstractRole
{
    public const ALLOW = 2;
    public const COMPLEMENTARY = 1;
    public const FORBIDDEN = 0;

    private string $roleName;
    private string $roleClass;

    public function __construct()
    {
        $class = new ReflectionClass($this);
        $this->roleName = $class->getShortName();
        $this->roleClass = $class->getName();
    }

    public static function hasPermission(int $permission, ?Closure $complementaryPermission = null): bool
    {
        switch ($permission) {
            case self::ALLOW:
                return true;
            case self::COMPLEMENTARY:
                if (is_null($complementaryPermission)) {
                    throw new MissingComplementaryClosureException("Complementary code not found");
                }
                return $complementaryPermission();
        }
        return false;
    }

    public static function validatePermission($permission): int
    {
        if (!in_array($permission, [self::ALLOW, self::COMPLEMENTARY, self::FORBIDDEN], true)) {
            return self::FORBIDDEN;
        }
        return $permission;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function getRoleClass(): string
    {
        return $this->roleClass;
    }
}
