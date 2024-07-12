<?php

namespace App\Http\Middleware;

use App\Models\IpBanned;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpBannedMiddware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ipBannedList = IpBanned::getListIpBanned();
        if (in_array($request->ip(), $ipBannedList)) {
            abort(429);
        }
        
        return $next($request);
    }
}
