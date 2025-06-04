<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        // Public endpoint - return all active organizations
        $organizations = Organization::where('is_active', true)
            ->withCount('events')
            ->orderBy('name')
            ->get();
            
        return response()->json($organizations);
    }

    public function adminIndex()
    {
        // Admin endpoint - return all organizations
        $organizations = Organization::withCount(['events', 'users'])
            ->orderBy('name')
            ->get();
            
        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:organizations,slug',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
        ]);

        try {
            // Create organization
            $organization = Organization::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'website' => $validated['website'] ?? null,
                'email' => $validated['contact_email'] ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Organization created successfully',
                'organization' => $organization
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create organization',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Organization $organization)
    {
        // Public endpoint - show organization details
        if (!$organization->is_active) {
            return response()->json(['message' => 'Organization not found'], 404);
        }
        
        return response()->json($organization);
    }

    public function update(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $organization->update($validated);

        return response()->json($organization);
    }

    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);
        $organization->delete();
        return response()->json(null, 204);
    }
} 