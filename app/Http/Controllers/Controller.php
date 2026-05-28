<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Get the current route prefix (admin or owner)
     */
    protected function getRoutePrefix(): string
    {
        $routeName = request()->route()?->getName() ?? '';
        
        if (str_starts_with($routeName, 'owner.')) {
            return 'owner';
        }
        
        return 'admin';
    }
}
