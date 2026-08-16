<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts a route to specific `users.role` values (admin/store/captain/
 * customer). This is separate from Spatie's `permission:`/`role:` middleware
 * used on the Admin routes — those check the permission package's own roles
 * table, which Store accounts are deliberately excluded from (see project
 * memory), so the Store panel needs its own simple account-type check
 * instead: `middleware('account_role:store')`.
 */
class EnsureAccountRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user() && in_array($request->user()->role, $roles, true), 403);

        return $next($request);
    }
}
