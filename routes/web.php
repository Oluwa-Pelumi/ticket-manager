<?php

use App\Models\Faq;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProgrammeController;

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return view('home', [
        'stats' => [
            'totalTickets'      => Ticket::count(),
            'openTickets'       => Ticket::where('status', 'open')->count(),
            'resolvedTickets'   => Ticket::where('status', 'closed')->count(),
            'inProgressTickets' => Ticket::where('status', 'in-progress')->count(),
        ],
        'faqs' => rescue(fn () => Faq::orderBy('order')->get(), []),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/submit-ticket', function () {
        return view('submit-ticket', [
            'categories' => rescue(fn () => \App\Models\Category::all(), []),
        ]);
    })->name('submit-ticket');

    Route::post('submit-ticket', [TicketController::class, 'save'])
        ->name('save-ticket');
});

Route::get('/check-status', function () {
    return view('check-status');
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

// RESTful routes used by the dashboard Alpine.js component
Route::middleware(['auth', 'verified'])->group(function () {
    // Static routes MUST be listed before wildcard {ticket} routes to avoid conflicts
    Route::delete('/tickets/bulk-delete', [TicketController::class, 'bulkDestroyTickets'])
        ->middleware('admin')
        ->name('tickets.bulk-destroy');
    Route::patch('/tickets/bulk-status', [TicketController::class, 'bulkUpdateTicketStatus'])
        ->middleware('staff')
        ->name('tickets.bulk-status');

    // Wildcard routes — registered after static ones
    Route::patch('/tickets/{ticket}/status/{status}', [TicketController::class, 'updateTicketStatus'])
        ->name('tickets.update-status');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroyTicket'])
        ->middleware('admin')
        ->name('tickets.destroy');
    Route::post('/tickets/{ticket}/comments', [TicketController::class, 'addComment'])
        ->name('tickets.add-comment');

    // Lightweight polling endpoint — returns [{id, status}] for the user's visible tickets
    Route::get('/tickets/statuses', [TicketController::class, 'ticketStatuses'])
        ->name('tickets.statuses');
});

Route::post('/tickets/{ticket}/comment', [TicketController::class, 'addComment'])
    ->name('add-comment');

Route::middleware(['auth', 'verified', 'staff'])->group(function () {
    Route::patch('bulk-update-ticket-status', [TicketController::class, 'bulkUpdateStatus'])
        ->name('bulk-update-ticket-status');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
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

    // Programme Management
    Route::get('/admin/programmes', [ProgrammeController::class, 'index'])->name('admin.programmes.index');
    Route::post('/admin/programmes', [ProgrammeController::class, 'store'])->name('admin.programmes.store');
    Route::patch('/admin/programmes/{programme}', [ProgrammeController::class, 'update'])->name('admin.programmes.update');
    Route::delete('/admin/programmes/{programme}', [ProgrammeController::class, 'destroy'])->name('admin.programmes.destroy');

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