<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Organization;
use Symfony\Component\HttpFoundation\Response;

class CheckOrganizationAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Get organization from route parameter
        $organization = $request->route('organization');
        
        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        if (!$organization->is_active) {
            return response()->json(['message' => 'Organization is not active'], 403);
        }

        // Check if authenticated user belongs to this organization
        if ($user && $user->organization_id !== $organization->id) {
            return response()->json(['message' => 'Unauthorized access to this organization'], 403);
        }

        return $next($request);
    }
} 