<?php

namespace App\Http\Middleware;

use App\Models\Visitor as ModelsVisitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class Visitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!str_contains($request->url(), 'admin')) {
            $canUpdateVisitor = Cache::get('visitor');

            if (!$canUpdateVisitor) {
                ModelsVisitor::create([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $ttl = now();
                $ttl->addMinutes(1);
                Cache::put('visitor', true, $ttl);
            }
        }

        return $next($request);
    }
}
