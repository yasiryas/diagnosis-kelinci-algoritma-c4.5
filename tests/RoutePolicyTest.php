<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

final class RoutePolicyTest
{
    public function testAdminPatternsCoverAdminAndMining(): void
    {
        $patterns = Config\RoutePolicy::protectedUriPatterns();

        expectTrue(in_array('admin', $patterns, true), 'admin must be protected');
        expectTrue(in_array('admin/*', $patterns, true), 'admin children must be protected');
        expectTrue(in_array('mining', $patterns, true), 'mining must be protected');
        expectTrue(in_array('mining/*', $patterns, true), 'mining children must be protected');
    }

    public function testAdminFilterUsesDedicatedFilterClass(): void
    {
        expectSame(App\Filters\AdminFilter::class, Config\RoutePolicy::adminFilterClass(), 'Admin alias must target AdminFilter');
    }
}
