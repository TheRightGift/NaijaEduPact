<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UniversityAdminController;
use App\Http\Controllers\UniversityAdmin\CampaignController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\JobController;

Auth::routes();

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // Add the resource route for jobs
    Route::resource('jobs', JobController::class);

    // Route to show the main mentorship dashboard page (for both mentors and mentees)
    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    
    // Route for the alumnus to update their mentor profile (availability and bio)
    Route::post('/mentorship/profile', [MentorshipController::class, 'updateProfile'])->name('mentorship.profile.update');
    
    // Route for the mentor to accept or decline a request
    Route::post('/mentorship/request/{mentorship}/respond', [MentorshipController::class, 'respondToRequest'])->name('mentorship.request.respond');

    // Add this line for students to send requests
    Route::post('/mentorship/request', [MentorshipController::class, 'store'])->name('mentorship.request.store');

    // Super Admin Routes
    Route::middleware(['role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/projects/pending', [SuperAdminController::class, 'pendingProjects'])->name('projects.pending');
        Route::post('/projects/{project}/approve', [SuperAdminController::class, 'approveProject'])->name('projects.approve');
        Route::post('/projects/{project}/reject', [SuperAdminController::class, 'rejectProject'])->name('projects.reject');
        // Add routes for University CRUD here
    });

    // University Admin Routes
    Route::middleware(['role:universityadmin'])->prefix('uadmin')->name('uadmin.')->group(function () {
        Route::get('/dashboard', [UniversityAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/projects/create', [UniversityAdminController::class, 'createProject'])->name('projects.create');
        Route::post('/projects', [UniversityAdminController::class, 'storeProject'])->name('projects.store');
        Route::resource('campaigns', \App\Http\Controllers\UniversityAdmin\CampaignController::class);
        Route::get('/analytics', [\App\Http\Controllers\UniversityAdmin\AnalyticsController::class, 'index'])->name('analytics.index');
    });
});


Route::get('/projects', function () {
    return view('projects.index');
})->name('projects.index');

Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');
