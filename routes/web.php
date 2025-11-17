<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Public Controllers ---
use App\Http\Controllers\Public\LandingPageController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\CampaignController;
// 1. ALIAS THE PUBLIC UNIVERSITY CONTROLLER
use App\Http\Controllers\Public\UniversityController as PublicUniversityController;

// --- Core Controllers ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\JobController;

// --- Admin Controllers ---
use App\Http\Controllers\SuperAdminController;
// 2. ALIAS THE SUPER ADMIN UNIVERSITY CONTROLLER
use App\Http\Controllers\SuperAdmin\UniversityController as SuperAdminUniversityController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\UniversityAdmin\ProjectTimelineUpdateController;
use App\Http\Controllers\UniversityAdminController;
use App\Http\Controllers\UniversityAdmin\AnalyticsController;
use App\Http\Controllers\UniversityAdmin\CampaignController as AdminCampaignController;
use App\Http\Controllers\UniversityAdmin\GeneralFundController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/campaigns/{campaign:slug}', [CampaignController::class, 'show'])->name('campaigns.show');

// 3. USE THE 'PublicUniversityController' ALIAS HERE
Route::get('/universities/{university:slug}', [PublicUniversityController::class, 'show'])->name('universities.show');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/my-donations', [HomeController::class, 'donationHistory'])->name('donations.history');
    Route::resource('jobs', JobController::class);
    
    // Mentorship
    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::post('/mentorship/profile', [MentorshipController::class, 'updateProfile'])->name('mentorship.profile.update');
    Route::post('/mentorship/request/{mentorship}/respond', [MentorshipController::class, 'respondToRequest'])->name('mentorship.request.respond');
    Route::post('/mentorship/request', [MentorshipController::class, 'store'])->name('mentorship.request.store');

    // Donation
    Route::post('/donate/start', [DonationController::class, 'startPayment'])->name('donate.start');
    Route::get('/donate/success', [DonationController::class, 'paymentSuccess'])->name('donate.success');
    Route::get('/donate/cancel', [DonationController::class, 'paymentCancel'])->name('donate.cancel');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Super Admin Routes
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects/pending', [SuperAdminController::class, 'pendingProjects'])->name('projects.pending');
    Route::post('/projects/{project}/approve', [SuperAdminController::class, 'approveProject'])->name('projects.approve');
    Route::post('/projects/{project}/reject', [SuperAdminController::class, 'rejectProject'])->name('projects.reject');
    
    // 4. USE THE 'SuperAdminUniversityController' ALIAS HERE
    Route::resource('universities', SuperAdminUniversityController::class);
    Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
});

// University Admin Routes
Route::middleware(['auth', 'role:universityadmin'])->prefix('uadmin')->name('uadmin.')->group(function () {
    Route::get('/dashboard', [UniversityAdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('projects', UniversityAdminController::class)->except(['show']);
    Route::post('projects/{project}/updates', [ProjectTimelineUpdateController::class, 'store'])->name('projects.updates.store');
    
    Route::resource('campaigns', AdminCampaignController::class);
    Route::post('/campaigns/{campaign}/add-project', [AdminCampaignController::class, 'addProject'])->name('campaigns.addProject');
    Route::post('/campaigns/{campaign}/add-challenge', [AdminCampaignController::class, 'addChallenge'])->name('campaigns.addChallenge');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('/general-fund', [GeneralFundController::class, 'edit'])->name('general-fund.edit');
    Route::put('/general-fund', [GeneralFundController::class, 'update'])->name('general-fund.update');
    Route::post('/general-fund/updates', [GeneralFundController::class, 'storeUpdate'])->name('general-fund.storeUpdate');
});