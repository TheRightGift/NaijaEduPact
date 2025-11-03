<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Public\LandingPageController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\CampaignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UniversityController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\UniversityAdmin\ProjectTimelineUpdateController;
use App\Http\Controllers\UniversityAdminController;
use App\Http\Controllers\UniversityAdmin\CampaignController as AdminCampaignController;;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\JobController;

Auth::routes();

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/campaigns/{campaign:slug}', [\App\Http\Controllers\Public\CampaignController::class, 'show'])->name('campaigns.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Add this new route for donation history
    Route::get('/my-donations', [HomeController::class, 'donationHistory'])->name('donations.history');
    
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

    // This route starts the payment session
    Route::post('/donate/start', [DonationController::class, 'startPayment'])
         ->name('donate.start');
         
    // Stripe redirects here on success
    Route::get('/donate/success', [DonationController::class, 'paymentSuccess'])
         ->name('donate.success');
         
    // Stripe redirects here on cancellation
    Route::get('/donate/cancel', [DonationController::class, 'paymentCancel'])
         ->name('donate.cancel');
    
    // Super Admin Routes
    Route::middleware(['role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/projects/pending', [SuperAdminController::class, 'pendingProjects'])->name('projects.pending');
        Route::post('/projects/{project}/approve', [SuperAdminController::class, 'approveProject'])->name('projects.approve');
        Route::post('/projects/{project}/reject', [SuperAdminController::class, 'rejectProject'])->name('projects.reject');
        // Resource route for managing universities
        Route::resource('universities', UniversityController::class);
        
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
    });

    // University Admin Routes
    Route::middleware(['role:universityadmin'])->prefix('uadmin')->name('uadmin.')->group(function () {
        Route::get('/dashboard', [UniversityAdminController::class, 'dashboard'])->name('dashboard');
        
        Route::resource('projects', UniversityAdminController::class)->except(['show']);
        
        Route::resource('campaigns', AdminCampaignController::class);
        Route::post('/campaigns/{campaign}/add-project', [AdminCampaignController::class, 'addProject'])->name('campaigns.addProject');
        Route::post('/campaigns/{campaign}/add-challenge', [AdminCampaignController::class, 'addChallenge'])->name('campaigns.addChallenge');

        Route::get('/analytics', [\App\Http\Controllers\UniversityAdmin\AnalyticsController::class, 'index'])->name('analytics.index');

        Route::post('projects/{project}/updates', [ProjectTimelineUpdateController::class, 'store'])->name('projects.updates.store');
    });
});


// Route::get('/projects', function () {
//     return view('projects.index');
// })->name('projects.index');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show'); // <-- ADD THIS


