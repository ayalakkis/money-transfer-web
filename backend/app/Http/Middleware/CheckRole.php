<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $allowedRoles = explode('|', $roles);
        $userRole = $request->user()->user_type;

        if (!in_array($userRole, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Required role: ' . $roles
            ], 403);
        }

        // Additional check for agents - must be approved
        if ($userRole === 'agent' && !$request->user()->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Agent account pending approval'
            ], 403);
        }

        return $next($request);
    }
}

