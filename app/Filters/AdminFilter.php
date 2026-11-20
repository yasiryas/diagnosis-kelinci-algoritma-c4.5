<?php

namespace App\Filters;

use Myth\Auth\Filters\RoleFilter;

class AdminFilter extends RoleFilter
{
    public function before(\CodeIgniter\HTTP\RequestInterface $request, $params = null)
    {
        return parent::before($request, ['admin']);
    }
}
