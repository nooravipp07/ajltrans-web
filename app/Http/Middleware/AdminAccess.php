<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return $request->expectsJson() 
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('admin.login');
        }

        $user = auth('admin')->user();
        $routeName = $request->route()->getName();
        
        // Define role permissions
        $permissions = [
            'superadmin' => ['*'], // Access everything
            'admin' => [
                'admin.dashboard',
                'admin.vehicles.*',
                'admin.bookings.*',
                'admin.customers.*',
                'admin.pricing.*',
                'admin.testimonials.*',
                'admin.posts.*',
                'admin.gallery.*',
                'admin.analytics.*',
                'admin.content.*',
                'admin.wa-templates.*',
                'admin.settings.*'
            ],
            'operator' => [
                'admin.dashboard',
                'admin.bookings.index',
                'admin.bookings.show',
                'admin.bookings.update-status'
            ],
        ];

        $userRole = $user->role;
        $allowedRoutes = $permissions[$userRole] ?? [];

        if (in_array('*', $allowedRoutes)) {
            return $next($request);
        }

        foreach ($allowedRoutes as $allowed) {
            if (str_contains($allowed, '*')) {
                $pattern = str_replace('*', '', $allowed);
                if (str_starts_with($routeName, $pattern)) {
                    return $next($request);
                }
            } else {
                if ($routeName === $allowed) {
                    return $next($request);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
