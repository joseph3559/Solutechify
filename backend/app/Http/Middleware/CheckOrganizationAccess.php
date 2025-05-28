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
        $organizationSlug = $request->route('tenant');
        $organization = Organization::where('slug', $organizationSlug)->first();

        if (!$organization) {
            return response()->json(['message' => 'Organization not found'], 404);
        }

        if (!$organization->is_active) {
            return response()->json(['message' => 'Organization is not active'], 403);
        }

        // If user is authenticated, check if they belong to the organization
        if ($request->user() && $request->user()->organization_id !== $organization->id) {
            return response()->json(['message' => 'Unauthorized access to this organization'], 403);
        }

        // Add organization to the request for later use
        $request->merge(['organization' => $organization]);

        return $next($request);
    }
} 