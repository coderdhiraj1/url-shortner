<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MemberController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        // invitations
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');

        // short url generation
        Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
        Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');

        // clients full view
        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        
        // short url full view
        Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');

        // exporting short urls
        Route::get('/short-urls/download', [ShortUrlController::class, 'download'])->name('short-urls.download');

        // member full view page
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');

    });

});


Route::get('/invitations/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');

Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect'])->name('short-urls.redirect');