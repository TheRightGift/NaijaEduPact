<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Public\LandingPageController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\CampaignController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UniversityAdminController;
use App\Http\Controllers\UniversityAdmin\CampaignController as AdminCampaignController;;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\JobController;

Auth::routes();

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/campaigns/{campaign:slug}', [\App\Http\Controllers\Public\CampaignController::class, 'show'])->name('campaigns.show');

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
        // This will manage all project routes: index, create, store, edit, update, destroy
        Route::resource('projects', UniversityAdminController::class)->except(['index', 'show']);
        
        Route::resource('campaigns', AdminCampaignController::class);
        Route::post('/campaigns/{campaign}/add-project', [AdminCampaignController::class, 'addProject'])->name('campaigns.addProject');
        Route::post('/campaigns/{campaign}/add-challenge', [AdminCampaignController::class, 'addChallenge'])->name('campaigns.addChallenge');

        Route::get('/analytics', [\App\Http\Controllers\UniversityAdmin\AnalyticsController::class, 'index'])->name('analytics.index');
    });
});


// Route::get('/projects', function () {
//     return view('projects.index');
// })->name('projects.index');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show'); // <-- ADD THIS


