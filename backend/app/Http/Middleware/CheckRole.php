<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $user = $request->user();
        
        Log::info('CheckRole Debug', [
            'requested_role' => $role,
            'user_role' => $user->role,
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        // Handle super_admin role check - allow admin, super_admin, or organizer
        if ($role === 'super_admin') {
            if (!in_array($user->role, ['admin', 'super_admin', 'organizer'])) {
                Log::warning('Role check failed for super_admin', [
                    'user_role' => $user->role,
                    'allowed_roles' => ['admin', 'super_admin', 'organizer']
                ]);
                return response()->json([
                    'message' => 'Unauthorized. This action requires admin privileges.',
                ], 403);
            }
        } else {
            // For other specific roles, check exact match
            if ($user->role !== $role) {
                Log::warning('Role check failed for specific role', [
                    'required_role' => $role,
                    'user_role' => $user->role
                ]);
                return response()->json([
                    'message' => 'Unauthorized. This action requires ' . $role . ' privileges.',
                ], 403);
            }
        }

        Log::info('Role check passed', ['role' => $role, 'user_role' => $user->role]);
        return $next($request);
    }
} 