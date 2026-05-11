<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale'));
        
        if ($request->has('lang')) {
            $newLocale = $request->get('lang');
            if (in_array($newLocale, ['en', 'id'])) {
                $locale = $newLocale;
                session(['locale' => $locale]);
            }
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
