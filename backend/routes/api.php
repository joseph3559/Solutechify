<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

// Public authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public organization routes
Route::get('/organizations', [OrganizationController::class, 'index']);
Route::get('/organizations/{organization:slug}', [OrganizationController::class, 'show']);

// Public event routes
Route::get('/events', [EventController::class, 'publicIndex']);
Route::get('/events/{event}', [EventController::class, 'publicShowById']);
Route::get('/organizations/{organization:slug}/events', [EventController::class, 'publicEvents']);
Route::get('/organizations/{organization:slug}/events/{event}', [EventController::class, 'publicShow']);
Route::post('/organizations/{organization:slug}/events/{event}/register', [AttendeeController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('organization');
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User dashboard routes
    Route::prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard']);
        Route::get('/events', [UserController::class, 'events']);
        Route::get('/calendar', [UserController::class, 'calendar']);
        Route::get('/profile', [UserController::class, 'profile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        Route::delete('/registrations/{registration}', [UserController::class, 'cancelRegistration']);
    });
    
    // Super admin routes for organization management
    Route::prefix('admin')->middleware('role:super_admin')->group(function () {
        Route::post('/organizations', [OrganizationController::class, 'store']);
        Route::get('/organizations', [OrganizationController::class, 'adminIndex']);
        Route::put('/organizations/{organization}', [OrganizationController::class, 'update']);
        Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy']);
    });
    
    // Admin dashboard routes (for admin, super_admin, organizer)
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [UserController::class, 'adminDashboard']);
    });
    
    // Organization-scoped routes for admins
    Route::prefix('admin/organizations/{organization}')->middleware('organization')->group(function () {
        // Events management (admin only)
        Route::apiResource('events', EventController::class)->except(['index']);
        
        // Attendees management (admin only)
        Route::get('events/{event}/attendees', [AttendeeController::class, 'index']);
        Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
    });
}); 