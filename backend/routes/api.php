<?php

use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrganizationController;
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
Route::get('/organizations/{organization:slug}/events', [EventController::class, 'publicEvents']);
Route::get('/organizations/{organization:slug}/events/{event}', [EventController::class, 'publicShow']);
Route::post('/organizations/{organization:slug}/events/{event}/register', [AttendeeController::class, 'register']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load('organization');
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Organization-scoped routes for admins
    Route::prefix('admin/organizations/{organization}')->middleware('organization')->group(function () {
        // Events management (admin only)
        Route::apiResource('events', EventController::class)->except(['index']);
        
        // Attendees management (admin only)
        Route::get('events/{event}/attendees', [AttendeeController::class, 'index']);
        Route::delete('events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);
    });
}); 