<?php

namespace App\Http\Middleware;

use Closure;

class HighlightMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $menus = config('menu');
        $routeName = $request->route()->getName();

        foreach ($menus as &$menu) {
            if ($routeName === $menu['route_name']) {
                $menu['active'] = true;
            }
            if ($menu['childs']) {
                foreach ($menu['childs'] as &$child) {
                    if ($child['route_name'] === $routeName) {
                        $child['active'] = true;
                        $menu['active'] = true;
                    }
                }
            }
        }

        view()->share('menus', $menus);
        return $next($request);
    }
}
