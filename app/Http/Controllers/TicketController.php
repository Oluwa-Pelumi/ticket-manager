<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TicketNotification;

/**
 * Core ticket lifecycle: submission, updates, comments, status changes, and search.
 */
class TicketController extends Controller
{
    /**
     * Display tickets scoped by the authenticated user's role.
     */
    public function index()
    {
        $user    = Auth::user();
        $tickets = $user->role === 'admin'
            ? Ticket::with(['user', 'comments', 'category'])->latest()->get()
            : ($user->role === 'support'
                ? Ticket::with(['user', 'comments', 'category'])->whereJsonContains('attended_to_by', $user->id)->latest()->get()
                : Ticket::where('user_id', $user->id)->with(['comments', 'category'])->latest()->get());

        return view('dashboard', [
            'tickets'    => $tickets,
            'categories' => rescue(fn() => Category::all(), []),
        ]);
    }

    /**
     * Create a new ticket, assign support, upload files, and notify the submitter.
     */
    public function save(Request $request)
    {
        // --- Validate submission ---
        $validated = $request->validate([
            'attachments.*' => [
                'nullable',
                'file',
                'max:5120', // 5MB, adjust as needed
                'extensions:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt',
            ],
            'content'                => 'required|string',
            'email'                  => 'required|email|max:255',
            'name'                   => 'required|string|max:255',
            'subject'                => 'required|string|max:255',
            'category_id'            => 'nullable|exists:categories,id',
            'priority'               => 'required|string|in:low,medium,high',
            'phone_number'        => ['nullable', 'string', 'regex:/^(\+234)?\d{1,10}$/'],
        ], [
            'phone_number.regex'  => 'The phone number must not exceed 10 digits.'
        ]);

        $user = Auth::user();

        // --- Sync phone number to user profile ---
        if ($user && $user->phone_number !== ($validated['phone_number'] ?? null)) {
            $user->update(['phone_number' => $validated['phone_number'] ?? null]);
        }

        // --- Assign to support staff with fewest open tickets ---
        $assignedSupportId = $this->assignToLeastBusySupport();

        // --- Persist ticket record ---
        $ticket = Ticket::create([
            'status'                 => 'open',
            'user_id'                => Auth::id(),
            'attended_to_by'         => $assignedSupportId,
            'name'                   => $validated['name'],
            'email'                  => $validated['email'],
            'content'                => $validated['content'],
            'subject'                => $validated['subject'],
            'priority'               => $validated['priority'],
            'phone_number'           => $validated['phone_number'] ?? null,
            'category_id'            => $validated['category_id'] ?? Category::where('slug', $validated['subject'])->first()?->id,
        ]);

        // --- Upload attached files ---
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            $username  = Str::slug($validated['name'], '_');
            $folder    = $username . '-' . ($user ? $user->id : 'guest');

            foreach ($request->file('attachments') as $index => $file) {
                $extension    = $file->getClientOriginalExtension();
                $filename     = $ticket->id . '_' . $index . '_' . time() . '.' . $extension;
                $filepath     = $file->storeAs('tickets/' . $folder, $filename, 'public');
                $attachmentPaths[] = $filepath;
            }
        }

        $ticket->update([
            'attachments' => $attachmentPaths
        ]);

        // --- Notify submitter via mail ---
        $notificationMessage = "Your ticket (Reference: {$ticket->hashid}) has been submitted successfully. Track it here: " . route('ticket.show', $ticket->hashid);

        $ticketSubject = ucwords(str_replace('_', ' ', $validated['subject']));

        if ($user) {
            $user->notify(new TicketNotification($ticketSubject, $notificationMessage, route('ticket.show', $ticket->hashid), $user->name));
        } else {
            \Illuminate\Support\Facades\Notification::route('mail', $validated['email'])
                ->notify(new TicketNotification($ticketSubject, $notificationMessage, route('ticket.show', $ticket->hashid), $validated['name']));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => "Ticket submitted! Your reference is {$ticket->hashid}.",
                'redirect' => route('ticket.show', $ticket->hashid),
                'hashid'   => $ticket->hashid,
            ]);
        }

        return redirect()->route('ticket.show', $ticket->hashid)->with('success', "Ticket submitted successfully. Your reference code is {$ticket->hashid}. You can bookmark this page to track your ticket.");
    }

    /**
     * Update ticket details and optionally append new attachments.
     */
    // public function update(Request $request, Ticket $ticket)
    // {
    //     // --- Authorization and status checks ---
    //     if ($ticket->user_id !== Auth::id()) {
    //         return back()->with('error', 'Unauthorized action.');
    //     }

    //     if ($ticket->status === 'closed') {
    //         return back()->with('error', 'Closed tickets cannot be edited.');
    //     }

    //     $validated = $request->validate([
                //     'attachments.*' => [
                //     'nullable',
                //     'file',
                //     'max:10240', // 10MB, adjust as needed
                //     'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx',
                // ],
    //         'content'                => 'required|string',
    //         'subject'                => 'required|string|max:255',
    //         'category_id'            => 'nullable|exists:categories,id',
    //         'priority'               => 'required|string|in:low,medium,high',
    //     ]);

    //     $updateData = [
    //         'subject'                => $validated['subject'],
    //         'content'                => $validated['content'],
    //         'priority'               => $validated['priority'],
    //         'category_id'            => $validated['category_id'] ?? Category::where('slug', $validated['subject'])->first()?->id,
    //     ];

    //     // --- Merge new attachment uploads with existing paths ---
    //     if ($request->hasFile('attachments')) {
    //         $user       = Auth::user();
    //         $username   = Str::slug($user->name, '_');
    //         $folder     = $username . '-' . $user->id;
    //         $attachmentPaths = $ticket->attachments ?? [];

    //         foreach ($request->file('attachments') as $index => $file) {
    //             $extension    = $file->getClientOriginalExtension();
    //             $filename     = $username . '_' . time() . '_' . $index . '.' . $extension;
    //             $filepath     = $file->storeAs('tickets/'. $folder, $filename, 'public');
    //             $attachmentPaths[] = $filepath;
    //         }
    //         $updateData['attachments'] = $attachmentPaths;
    //     }

    //     $ticket->update($updateData);

    //     return back()->with('success', 'Ticket updated successfully.');
    // }

    /**
     * Update a single ticket's status and optionally reassign support staff.
     */
    public function updateStatus(Request $request)
    {
        // --- Authorization ---
        if (!Auth::user()->isAdmin() && !Auth::user()->isSupport()) {
            \Illuminate\Support\Facades\Log::info('Unauthorized action by user: ' . Auth::id() . ' trying to update ticket: ' . $request->id);
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'attended_to_by' => 'nullable|exists:users,id',
            'id'             => 'required|exists:tickets,id',
            'status'         => 'required|string|in:open,in-progress,closed',
        ]);

        $ticket = Ticket::findOrFail($validated['id']);

        // --- Build update payload with auto-assignment ---
        if ($request->has('attended_to_by') && $validated['attended_to_by']) {
            $ticket->addAttendant($validated['attended_to_by']);
        } else if ((Auth::user()->isAdmin() || Auth::user()->isSupport()) && empty($ticket->attended_to_by)) {
            // Automatically assign to current admin if not already assigned
            $ticket->addAttendant(Auth::id());
        }

        $oldStatus = $ticket->status;

        // --- Re-assign if transitioning from closed to open ---
        if ($oldStatus === 'closed' && in_array($validated['status'], ['open', 'in-progress']) && !$request->has('attended_to_by')) {
             $newSupportId = $this->assignToLeastBusySupport();
             if ($newSupportId) {
                 $ticket->addAttendant($newSupportId);
             }
        }

        $ticket->update(['status' => $validated['status']]);

        // --- Notify ticket owner when closed ---
        if ($oldStatus !== 'closed' && $validated['status'] === 'closed' && $ticket->user_id !== Auth::id()) {
            $notificationMsg = "Your ticket (Reference: {$ticket->hashid}) has been closed.\nView here: " . route('ticket.show', $ticket->hashid);
            $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

            if ($ticket->user) {
                $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->user->name, 'ticket_closed'));
            } else if ($ticket->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                    ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->name, 'ticket_closed'));
            }
        }

        return back()->with('success', 'Ticket updated successfully.');
    }

    /**
     * Delete multiple tickets and their associated files (admin only).
     */
    public function bulkDelete(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:tickets,id',
        ]);

        $tickets = Ticket::query()->whereIn('id', $validated['ids'], 'and', false)->get();

        foreach ($tickets as $ticket) {
            if ($ticket->filename) {
                Storage::disk('public')->delete($ticket->filename);
            }
            $ticket->delete();
        }

        return back()->with('success', count($validated['ids']) . ' tickets deleted successfully.');
    }

    /**
     * Update status for multiple tickets and notify owners when closed.
     */
    public function bulkUpdateStatus(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSupport()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:tickets,id',
            'status' => 'required|string|in:open,in-progress,closed',
        ]);

        $tickets = Ticket::whereIn('id', $validated['ids'])->get();

        // --- Bulk status update with assignment ---
        foreach ($tickets as $ticket) {
            $oldStatus = $ticket->status;

            // Auto-assign to current user if empty
            if (empty($ticket->attended_to_by)) {
                $ticket->addAttendant(Auth::id());
            }

            // Re-assign if transitioning from closed to open
            if ($oldStatus === 'closed' && in_array($validated['status'], ['open', 'in-progress'])) {
                $newSupportId = $this->assignToLeastBusySupport();
                if ($newSupportId) {
                    $ticket->addAttendant($newSupportId);
                }
            }

            $ticket->update(['status' => $validated['status']]);
        }

        // --- Notify owners of newly closed tickets ---
        if ($validated['status'] === 'closed') {
            foreach ($tickets as $ticket) {
                if ($ticket->status !== 'closed' && $ticket->user_id !== Auth::id()) {
                    $notificationMsg = "Your ticket (ID: {$ticket->id}) has been closed.\nView here: " . route('ticket.show', $ticket->id);
                    $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

                    if ($ticket->user) {
                        $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->user->name, 'ticket_closed'));
                    } else if ($ticket->email) {
                        \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                            ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->name, 'ticket_closed'));
                    }
                }
            }
        }

        return back()->with('success', 'Status updated for ' . count($validated['ids']) . ' tickets.');
    }

    /**
     * Delete a single ticket and its file attachment (admin only).
     */
    public function deleteTicket(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $ticket = Ticket::findOrFail($request->id);

        if ($ticket->filename) {
            Storage::disk('public')->delete($ticket->filename);
        }

        $ticket->delete();

        return back()->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Add a comment to a ticket.
     */
    public function addComment(Request $request, Ticket $ticket)
    {
        // --- Authorization for Supports ---
        if (Auth::user() && Auth::user()->isSupport() && !Auth::user()->isAdmin()) {
            if ($ticket->attendant && Auth::id() !== $ticket->attendant->id) {
                return back()->with('error', 'You are not the currently assigned support for this ticket and cannot reply.');
            }
        }

        $validated = $request->validate([
            'attachments.*' => [
                'nullable',
                'file',
                'max:5120', // 5MB, adjust as needed
                'extensions:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt',
            ],
            'content'  => 'required|string',
        ]);

        // --- Upload comment attachments ---
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            $user      = Auth::user();
            $username  = $user ? Str::slug($user->name, '_') : 'guest';
            $folder    = 'comments/' . $username . '-' . ($user ? $user->id : 'guest');

            foreach ($request->file('attachments') as $index => $file) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = time() . '_' . $index . '.' . $extension;
                            $filepath  = $file->storeAs($folder, $filename, 'public');
                $attachmentPaths[]          = $filepath;
            }
        }

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'attachments'  => $attachmentPaths,
            'content' => $validated['content'],
        ]);

        // --- Notify ticket owner when staff replies ---
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isSupport()) && $ticket->user_id !== Auth::id()) {
            $notificationMsg = "Subject: {$ticket->subject}\nView here: " . route('ticket.show', $ticket->id);

            $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

            if ($ticket->user) {
                $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->user->name, 'ticket_is_replied'));
            } else if ($ticket->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                    ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->name, 'ticket_is_replied'));
            }
        }

        // --- Auto-transition open tickets to in-progress on staff reply ---
        if ($ticket->status === 'open' && Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isSupport())) {
            $ticket->addAttendant(Auth::id());
            $ticket->update(['status' => 'in-progress']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $comment->load('user');
            return response()->json([
                'success' => true,
                'comment' => [
                    'id'          => $comment->id,
                    'content'     => $comment->content,
                    'attachments' => $comment->attachments ?? [],
                    'created_at'  => $comment->created_at->toISOString(),
                    'user'        => $comment->user ? [
                        'id'   => $comment->user->id,
                        'name' => $comment->user->name,
                        'role' => $comment->user->role,
                    ] : null,
                ],
            ]);
        }

        return back()->with('success', 'Comment added successfully.');
    }

    /** Search for a ticket by its public hashid reference. */
    public function searchTicketsByReference(Request $request)
    {
        $request->validate([
            'reference' => 'required|string|min:8'
        ]);

        $id = \Vinkla\Hashids\Facades\Hashids::decode($request->reference);

        if (empty($id)) {
            $tickets = collect();
        } else {
            $tickets = Ticket::where('id', $id[0])
                ->with(['category'])
                ->get();
        }

        return view('check-status', [
            'tickets'           => $tickets,
            'categories'        => Category::all(),
            'searchedReference' => $request->reference,
        ]);
    }

    /** Display a single ticket with its relationships loaded. */
    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'comments.user', 'category']);

        return view('ticket.show', [
            'ticket'     => $ticket,
            'categories' => Category::all(),
        ]);
    }

    /**
     * RESTful status update — PATCH /tickets/{ticket}/status/{status}
     * Used by the dashboard Alpine.js component.
     */
    public function updateTicketStatus(Request $request, Ticket $ticket, string $status)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSupport()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!in_array($status, ['open', 'in-progress', 'closed'])) {
            return response()->json(['error' => 'Invalid status'], 422);
        }

        // Auto-assign if not already assigned
        if (empty($ticket->attended_to_by)) {
            $ticket->addAttendant(Auth::id());
        }

        $oldStatus = $ticket->status;

        // --- Re-assign if transitioning from closed to open ---
        if ($oldStatus === 'closed' && in_array($status, ['open', 'in-progress'])) {
             $newSupportId = $this->assignToLeastBusySupport();
             if ($newSupportId) {
                 $ticket->addAttendant($newSupportId);
             }
        }

        $ticket->update(['status' => $status]);

        // Notify owner when closed
        if ($oldStatus !== 'closed' && $status === 'closed' && $ticket->user_id !== Auth::id()) {
            $notificationMsg = "Your ticket (Reference: {$ticket->hashid}) has been closed.\nView here: " . route('ticket.show', $ticket->hashid);
            $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

            if ($ticket->user) {
                $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->user->name, 'ticket_closed'));
            } elseif ($ticket->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                    ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->name, 'ticket_closed'));
            }
        }

        return response()->json(['success' => true, 'status' => $status]);
    }

    /**
     * RESTful single ticket delete — DELETE /tickets/{ticket}
     * Used by the dashboard Alpine.js component.
     */
    public function destroyTicket(Ticket $ticket)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($ticket->filename) {
            Storage::disk('public')->delete($ticket->filename);
        }
        if (!empty($ticket->attachments)) {
            foreach ($ticket->attachments as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $ticket->delete();

        return response()->json(['success' => true]);
    }

    /**
     * RESTful bulk delete — DELETE /tickets/bulk-delete
     * Used by the dashboard Alpine.js component.
     */
    public function bulkDestroyTickets(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:tickets,id',
        ]);

        $tickets = Ticket::whereIn('id', $validated['ids'])->get();

        foreach ($tickets as $ticket) {
            if ($ticket->filename) {
                Storage::disk('public')->delete($ticket->filename);
            }
            if (!empty($ticket->attachments)) {
                foreach ($ticket->attachments as $img) {
                    Storage::disk('public')->delete($img);
                }
            }
            $ticket->delete();
        }

        return response()->json(['success' => true, 'deleted' => count($validated['ids'])]);
    }

    /**
     * RESTful bulk status update — PATCH /tickets/bulk-status
     * Used by the dashboard Alpine.js component.
     */
    public function bulkUpdateTicketStatus(Request $request)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSupport()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:tickets,id',
            'status' => 'required|string|in:open,in-progress,closed',
        ]);

        $tickets = Ticket::whereIn('id', $validated['ids'])->get();

        foreach ($tickets as $ticket) {
            $oldStatus = $ticket->status;

            if (empty($ticket->attended_to_by)) {
                $ticket->addAttendant(Auth::id());
            }

            if ($oldStatus === 'closed' && in_array($validated['status'], ['open', 'in-progress'])) {
                $newSupportId = $this->assignToLeastBusySupport();
                if ($newSupportId) {
                    $ticket->addAttendant($newSupportId);
                }
            }

            $ticket->update(['status' => $validated['status']]);
        }

        // Notify owners of newly closed tickets
        if ($validated['status'] === 'closed') {
            foreach ($tickets as $ticket) {
                if ($ticket->status !== 'closed' && $ticket->user_id !== Auth::id()) {
                    $notificationMsg = "Your ticket (Reference: {$ticket->hashid}) has been closed.\nView here: " . route('ticket.show', $ticket->hashid);
                    $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

                    if ($ticket->user) {
                        $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->user->name, 'ticket_closed'));
                    } elseif ($ticket->email) {
                        \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                            ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->name, 'ticket_closed'));
                    }
                }
            }
        }

        return response()->json(['success' => true, 'status' => $validated['status']]);
    }

    /**
     * Helper to find the support staff member with the fewest active tickets.
     */
    private function assignToLeastBusySupport()
    {
        $supports = \App\Models\User::where('role', 'support')->get();

        if ($supports->isEmpty()) {
            return null;
        }

        $activeTickets = Ticket::whereIn('status', ['open', 'in-progress'])->get();
        $counts = [];

        foreach ($supports as $support) {
            $counts[$support->id] = 0;
        }

        foreach ($activeTickets as $ticket) {
            $attended = $ticket->attended_to_by ?? [];
            foreach ($attended as $suppId) {
                if (isset($counts[$suppId])) {
                    $counts[$suppId]++;
                }
            }
        }

        foreach ($supports as $support) {
            $support->setAttribute('assigned_tickets_count', $counts[$support->id]);
        }

        $minCount = $supports->min('assigned_tickets_count');
        return $supports->where('assigned_tickets_count', $minCount)->random()->id;
    }
}
