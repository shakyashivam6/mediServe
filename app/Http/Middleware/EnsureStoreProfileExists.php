<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A role=store User should always have a matching `stores` row — Admin\
 * StoreController (and now UserSeeder) always create both together. This
 * is a safety net for that invariant ever being violated (bad data, a
 * manual DB edit, a future creation path that forgets the pairing) so a
 * Store panel screen fails with a clear message instead of a raw
 * "Attempt to read property on null" ErrorException.
 */
class EnsureStoreProfileExists
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            $request->user()?->role === 'store' && ! $request->user()->store,
            403,
            'This Store account has no store profile set up — contact Admin to fix it.'
        );

        return $next($request);
    }
}
