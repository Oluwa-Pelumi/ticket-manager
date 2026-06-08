<?php

use App\Models\Faq;
use Inertia\Inertia;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return Inertia::render('Home/index', [
        'stats' => [
            'totalTickets'      => Ticket::count(),
            'openTickets'       => Ticket::where('status', 'open')->count(),
            'resolvedTickets'   => Ticket::where('status', 'closed')->count(),
            'inProgressTickets' => Ticket::where('status', 'in-progress')->count(),
        ],
        'faqs' => rescue(fn () => Faq::orderBy('order')->get(), []),
    ]);
})->name('home');

Route::get('/submit-ticket', function () {
    return Inertia::render('SubmitTicket/index', [
        'categories' => rescue(fn () => \App\Models\Category::all(), []),
    ]);
})->name('submit-ticket');

Route::post('submit-ticket', [TicketController::class, 'save'])
    ->name('save-ticket');

Route::get('/check-status', function () {
    return Inertia::render('CheckStatus/index');
})->name('check-status');

Route::post('/search-tickets', [TicketController::class, 'searchTicketsByReference'])
    ->name('search-tickets');

Route::get('/ticket/{ticket}', [TicketController::class, 'show'])
    ->name('ticket.show');

Route::patch('update-ticket-status', [TicketController::class, 'updateStatus'])
    ->name('update-ticket-status');

Route::get('/dashboard', [TicketController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::patch('/update-ticket/{ticket}', [TicketController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('update-ticket');

Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
    ->name('add-comment');

Route::middleware(['auth', 'staff'])->group(function () {
    Route::patch('bulk-update-ticket-status', [TicketController::class, 'bulkUpdateStatus'])
        ->name('bulk-update-ticket-status');

    Route::patch('/tickets/{ticket}/activate-order', [TicketController::class, 'activateOrder'])
    ->name('tickets.activate-order');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::delete('delete-ticket', [TicketController::class, 'deleteTicket'])
        ->name('delete-ticket');

    Route::delete('bulk-delete-tickets', [TicketController::class, 'bulkDelete'])
        ->name('bulk-delete-tickets');

    // User Management
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.update-role');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Category Management
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // FAQ Management
    Route::get('/admin/faqs', [\App\Http\Controllers\FaqController::class, 'index'])->name('admin.faqs.index');
    Route::post('/admin/faqs', [\App\Http\Controllers\FaqController::class, 'store'])->name('admin.faqs.store');
    Route::patch('/admin/faqs/{faq}', [\App\Http\Controllers\FaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/admin/faqs/{faq}', [\App\Http\Controllers\FaqController::class, 'destroy'])->name('admin.faqs.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
