<?php

namespace App\Core\DataType;

class RoleDataType
{
    const ROLE_ROOT = 'ROLE_ROOT';
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_ADMIN_POST = 'ROLE_ADMIN_POST';
    const ROLE_ADMIN_PAGE = 'ROLE_ADMIN_PAGE';
    const ROLE_ADMIN_SETTING = 'ROLE_ADMIN_SETTING';

    public static function getNameByRole(string $role): string
    {
        return match ($role) {
            self::ROLE_ROOT => "Root",
            self::ROLE_ADMIN => "Admin",
            self::ROLE_ADMIN_POST => "Admin Post",
            self::ROLE_ADMIN_PAGE => "Admin Page",
            self::ROLE_ADMIN_SETTING => "Admin Setting",
            default => "Not supported role",
        };
    }
}