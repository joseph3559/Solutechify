<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrganizationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\SetResponseContentType;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Central domain routes (not tenant-specific)
Route::post('organizations', [OrganizationController::class, 'store']);

// Tenant-specific routes
Route::middleware(['tenant'])->group(function () {
    // Event routes
    Route::get('events/upcoming', [EventController::class, 'upcoming']);
    Route::get('events/active', [EventController::class, 'active']);
    Route::apiResource('events', EventController::class)->except(['store']);

    // Attendee routes
    Route::get('events/{event}/attendees', [AttendeeController::class, 'eventAttendees']);
    Route::patch('attendees/{attendee}/status/{status}', [AttendeeController::class, 'updateStatus']);
    Route::apiResource('attendees', AttendeeController::class);
});

Route::middleware(['tenancy', SetResponseContentType::class . ':application/json'])->group(function () {
    Route::get('/_test_tenant_route', function () {
        return response()->json(['message' => 'Tenant route test successful']);
    });

    Route::post('/tenants', [TenantController::class, 'store']);
    Route::get('/tenants/{tenantId}/domains', [TenantController::class, 'listDomains']);
    Route::post('/tenants/{tenantId}/domains', [TenantController::class, 'addDomain']);
    Route::delete('/tenants/{tenantId}/domains/{domainName}', [TenantController::class, 'deleteDomain']);

    Route::post('events', [EventController::class, 'store'])->name('events.store');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Organization routes
    Route::apiResource('organizations', OrganizationController::class);
    
    // Organization-scoped routes
    Route::prefix('organizations/{organization}')->group(function () {
        // Events
        Route::apiResource('events', EventController::class);
        
        // Attendees
        Route::get('events/{event}/attendees', [AttendeeController::class, 'index']);
        Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
    });

    // Admin routes
    Route::middleware(['auth:sanctum', 'organization'])->prefix('{tenant}/admin')->group(function () {
        Route::get('/dashboard', [OrganizationController::class, 'dashboard']);
        Route::apiResource('events', EventController::class)->except(['index', 'show']);
        Route::get('/attendees', [EventController::class, 'attendees']);
    });
});

// Public event routes
Route::get('/{organization:slug}/events', [EventController::class, 'publicEvents']);
Route::post('/{organization:slug}/events/{event}/register', [AttendeeController::class, 'register']);

// Organization-specific public routes
Route::prefix('{tenant}')->middleware(['organization'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);
    Route::post('/events/{event}/register', [EventController::class, 'registerAttendee']);
}); 