<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CampaignController;

// Publicly accessible route to get all active projects
Route::get('/projects', [ProjectController::class, 'index']);

// Example of a protected route for when a user donates
Route::middleware('auth:api')->post('/donations', function (Request $request) {
    // Donation logic will go here later
    return $request->user();
});

Route::get('/campaigns/{campaign:slug}/status', [CampaignController::class, 'status']);
