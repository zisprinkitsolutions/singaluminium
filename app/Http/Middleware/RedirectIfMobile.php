<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfMobile
{
    public function handle(Request $request, Closure $next)
    {
        $isMobile = preg_match(
            '/(android|iphone|ipad|ipod|blackberry|webos|mobile)/i',
            $request->header('User-Agent')
        );
                view()->share('isMobile', $isMobile);


        if ($isMobile) {
            // Allowed mobile routes
            $allowedRoutes = [
                'requisition/requisitions*',  // requisition module
                'find-project-task',          // find project task
                'find-project-task-item',
                'requisition/requistion/search',    // find project task item
                'requisition/requisition-rejected',
                'requisition/requisition-approve*',
                'requisition/mobile/requisition*'
            ];

            if ($request->is($allowedRoutes)) {
                return $next($request);
            }

            // Redirect all other mobile requests
            return redirect('/requisition/requisitions/mobile/index');
        }

        return $next($request);
    }
}
