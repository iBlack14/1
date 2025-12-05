<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DomainController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('quotations', QuotationController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy']);
    Route::get('/quotations/{quotation}/download', [QuotationController::class, 'downloadPDF'])->name('quotations.download');
    Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendEmail'])->name('quotations.send-email');
    Route::get('/quotations-export', [QuotationController::class, 'export'])->name('quotations.export');

    // Domains Management
    Route::resource('domains', App\Http\Controllers\DomainController::class);

    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::get('/api/reminders', [QuotationController::class, 'getReminders'])->name('api.reminders');
});

require __DIR__.'/auth.php';
