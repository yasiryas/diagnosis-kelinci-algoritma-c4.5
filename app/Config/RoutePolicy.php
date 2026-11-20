<?php

namespace Config;

use App\Filters\AdminFilter;

class RoutePolicy
{
    public const PROTECTED_URI_PATTERNS = [
        'admin',
        'admin/*',
        'mining',
        'mining/*',
    ];

    public const ADMIN_FILTER = AdminFilter::class;

    public static function protectedUriPatterns(): array
    {
        return self::PROTECTED_URI_PATTERNS;
    }

    public static function adminFilterClass(): string
    {
        return self::ADMIN_FILTER;
    }
}
