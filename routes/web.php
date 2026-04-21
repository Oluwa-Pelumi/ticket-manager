<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return Inertia::render('Home/index');
})->name('home');

Route::get('/submit-ticket', function () {
    return Inertia::render('SubmitTicket/index');
})->middleware(['auth', 'verified'])
->name('submit-ticket');

Route::post('submit-ticket', [TicketController::class, 'save'])
    ->name('save-ticket');

Route::patch('edit-ticket', [TicketController::class, 'edit-ticket'])
->name('edit-ticket');

Route::get('/dashboard', [TicketController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::delete('delete-ticket', [TicketController::class, 'deleteTicket'])
        ->name('delete-ticket');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
