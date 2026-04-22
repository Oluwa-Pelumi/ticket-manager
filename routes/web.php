<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;

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

Route::patch('update-ticket-status', [TicketController::class, 'updateStatus'])
    ->name('update-ticket-status');

Route::get('/dashboard', [TicketController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/update-ticket/{ticket}', [TicketController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('update-ticket');

Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
    ->middleware(['auth', 'verified'])
    ->name('add-comment');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::delete('delete-ticket', [TicketController::class, 'deleteTicket'])
        ->name('delete-ticket');

    Route::delete('bulk-delete-tickets', [TicketController::class, 'bulkDelete'])
        ->name('bulk-delete-tickets');

    Route::patch('bulk-update-ticket-status', [TicketController::class, 'bulkUpdateStatus'])
        ->name('bulk-update-ticket-status');

    // User Management
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.update-role');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
