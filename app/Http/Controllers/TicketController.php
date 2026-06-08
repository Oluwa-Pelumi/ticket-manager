<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TicketNotification;

class TicketController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        $user    = Auth::user();
        $tickets = $user->role === 'admin' ? Ticket::with(['user', 'attendant', 'comments', 'category'])->latest()->get() //admin gets to see all tickets in the dashboard
            : ($user->role === 'support'
                ? Ticket::with(['user', 'attendant', 'comments', 'category'])->where('attended_to_by', $user->id)->latest()->get() //each particular support only sees the ticket theyve been assigned to
                : Ticket::where('user_id', $user->id)->with(['attendant', 'comments', 'category'])->latest()->get()); //authenticated users see only their own tickets

        return Inertia::render('Dashboard/index', [
            'tickets'    => $tickets,
            'categories' => rescue(fn() => Category::all(), []),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'custom_recurrence_date' => 'nullable|date',
            'images'                 => 'nullable|array',
            'images.*'               => 'image|max:5120',
            'content'                => 'required|string',
            'recurrence_period'      => 'nullable|string',
            'email'                  => 'required|email|max:255',
            'name'                   => 'required|string|max:255',
            'subject'                => 'required|string|max:255',
            'category_id'            => 'nullable|exists:categories,id',
            'priority'               => 'required|string|in:low,medium,high',
            'order_type'             => 'nullable|string|in:one-time,recurrent',
            'whatsapp_number'        => ['nullable', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
        ], [
            'whatsapp_number.regex'  => 'The WhatsApp number must be a valid international phone number (e.g., +2348000000000).'
        ]);

        $user = Auth::user();

        if ($user && $user->whatsapp_number !== $validated['whatsapp_number']) {
            $user->update(['whatsapp_number' => $validated['whatsapp_number']]);
        }

        $supports = \App\Models\User::where('role', 'support')
            ->withCount(['assignedTickets' => function ($query) {
                $query->whereIn('status', ['open', 'in-progress'], 'and', false);
            }])
            ->get();

        $assignedSupportId = null;
        if ($supports->isNotEmpty()) {
            $minCount = $supports->min('assigned_tickets_count');
            $assignedSupportId = $supports->where('assigned_tickets_count', $minCount)->random()->id;
        }

        $ticket = Ticket::create([
            'status'                 => 'open',
            'user_id'                => Auth::id(),
            'attended_to_by'         => $assignedSupportId,
            'name'                   => $validated['name'],
            'email'                  => $validated['email'],
            'content'                => $validated['content'],
            'subject'                => $validated['subject'],
            'priority'               => $validated['priority'],
            'whatsapp_number'        => $validated['whatsapp_number'],
            'order_type'             => $validated['order_type'] ?? null,
            'recurrence_period'      => $validated['recurrence_period'] ?? null,
            'custom_recurrence_date' => $validated['custom_recurrence_date'] ?? null,
            'category_id'            => $validated['category_id'] ?? Category::where('slug', $validated['subject'])->first()?->id,
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $username  = Str::slug($validated['name'], '_');
            $folder    = $username . '-' . ($user ? $user->id : 'guest');

            foreach ($request->file('images') as $index => $file) {
                $extension    = $file->getClientOriginalExtension();
                $filename     = $ticket->id . '_' . $index . '_' . time() . '.' . $extension;
                $filepath     = $file->storeAs('tickets/' . $folder, $filename, 'public');
                $imagePaths[] = $filepath;
            }
        }

        $ticket->update([
            'images' => $imagePaths
        ]);

        $notificationMessage = "Your ticket (Reference: {$ticket->hashid}) has been submitted successfully. Track it here: " . route('ticket.show', $ticket->hashid);

        $ticketSubject = ucwords(str_replace('_', ' ', $validated['subject']));

        if ($user) {
            $user->notify(new TicketNotification($ticketSubject, $notificationMessage, route('ticket.show', $ticket->id), $user->name));
        } else {
            \Illuminate\Support\Facades\Notification::route('mail', $validated['email'])
                ->route('whatsapp', $validated['whatsapp_number'])
                ->notify(new TicketNotification($ticketSubject, $notificationMessage, route('ticket.show', $ticket->hashid), $validated['name']));
        }

        return redirect()->route('ticket.show', $ticket->hashid)->with('success', "Ticket submitted successfully. Your reference code is {$ticket->hashid}. You can bookmark this page to track your ticket.");
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Ticket $ticket
     * @return void
     */
    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        if ($ticket->status === 'closed') {
            return back()->with('error', 'Closed tickets cannot be edited.');
        }

        $validated = $request->validate([
            'custom_recurrence_date' => 'nullable|date',
            'images'                 => 'nullable|array',
            'images.*'               => 'image|max:5120',
            'content'                => 'required|string',
            'recurrence_period'      => 'nullable|string',
            'subject'                => 'required|string|max:255',
            'category_id'            => 'nullable|exists:categories,id',
            'priority'               => 'required|string|in:low,medium,high',
            'order_type'             => 'nullable|string|in:one-time,recurrent',
        ]);

        $updateData = [
            'subject'                => $validated['subject'],
            'content'                => $validated['content'],
            'priority'               => $validated['priority'],
            'order_type'             => $validated['order_type'] ?? null,
            'recurrence_period'      => $validated['recurrence_period'] ?? null,
            'custom_recurrence_date' => $validated['custom_recurrence_date'] ?? null,
            'category_id'            => $validated['category_id'] ?? Category::where('slug', $validated['subject'])->first()?->id,
        ];

        if ($request->hasFile('images')) {
            $user       = Auth::user();
            $username   = Str::slug($user->name, '_');
            $folder     = $username . '-' . $user->id;
            $imagePaths = $ticket->images ?? [];

            foreach ($request->file('images') as $index => $file) {
                $extension    = $file->getClientOriginalExtension();
                $filename     = $username . '_' . time() . '_' . $index . '.' . $extension;
                $filepath     = $file->storeAs('tickets/'. $folder, $filename, 'public');
                $imagePaths[] = $filepath;
            }
            $updateData['images'] = $imagePaths;
        }

        $ticket->update($updateData);

        return back()->with('success', 'Ticket updated successfully.');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function updateStatus(Request $request)
    {
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

        $updateData = ['status' => $validated['status']];
        if ($request->has('attended_to_by')) {
            $updateData['attended_to_by'] = $validated['attended_to_by'];
        } else if ((Auth::user()->isAdmin() || Auth::user()->isSupport()) && !$ticket->attended_to_by) {
            // Automatically assign to current admin if not already assigned
            $updateData['attended_to_by'] = Auth::id();
        }

        $oldStatus = $ticket->status;
        $ticket->update($updateData);

        if ($oldStatus !== 'closed' && $validated['status'] === 'closed' && $ticket->user_id !== Auth::id()) {
            $notificationMsg = "Your ticket (Reference: {$ticket->hashid}) has been closed.\nView here: " . route('ticket.show', $ticket->hashid);
            $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

            if ($ticket->user) {
                $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->user->name, 'ticket_closed'));
            } else if ($ticket->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                    ->route('whatsapp', $ticket->whatsapp_number)
                    ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->hashid), $ticket->name, 'ticket_closed'));
            }
        }

        return back()->with('success', 'Ticket updated successfully.');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
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
     * Undocumented function
     *
     * @param Request $request
     * @return void
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

        \Illuminate\Support\Facades\DB::table('tickets')->whereIn('id', $validated['ids'])->update([
            'status'         => $validated['status'],
            'attended_to_by' => Auth::id(),             // Assign to current admin
        ]);

        if ($validated['status'] === 'closed') {
            foreach ($tickets as $ticket) {
                if ($ticket->status !== 'closed' && $ticket->user_id !== Auth::id()) {
                    $notificationMsg = "Your ticket (ID: {$ticket->id}) has been closed.\nView here: " . route('ticket.show', $ticket->id);
                    $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

                    if ($ticket->user) {
                        $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->user->name, 'ticket_closed'));
                    } else if ($ticket->email) {
                        \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                            ->route('whatsapp', $ticket->whatsapp_number)
                            ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->name, 'ticket_closed'));
                    }
                }
            }
        }

        return back()->with('success', 'Status updated for ' . count($validated['ids']) . ' tickets.');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
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
        $validated = $request->validate([
            'images'   => 'nullable|array',
            'images.*' => 'image|max:5120',
            'content'  => 'required|string',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $user      = Auth::user();
            $username  = $user ? Str::slug($user->name, '_') : 'guest';
            $folder    = 'comments/' . $username . '-' . ($user ? $user->id : 'guest');

            foreach ($request->file('images') as $index => $file) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = time() . '_' . $index . '.' . $extension;
                            $filepath  = $file->storeAs($folder, $filename, 'public');
                $imagePaths[]          = $filepath;
            }
        }

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'images'  => $imagePaths,
            'content' => $validated['content'],
        ]);

        // If an admin replies, notify the ticket owner
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isSupport()) && $ticket->user_id !== Auth::id()) {
            $notificationMsg = "Subject: {$ticket->subject}\nView here: " . route('ticket.show', $ticket->id);

            $ticketSubject = ucwords(str_replace('_', ' ', $ticket->subject));

            if ($ticket->user) {
                $ticket->user->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->user->name, 'ticket_is_replied'));
            } else if ($ticket->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $ticket->email)
                    ->route('whatsapp', $ticket->whatsapp_number)
                    ->notify(new TicketNotification($ticketSubject, $notificationMsg, route('ticket.show', $ticket->id), $ticket->name, 'ticket_is_replied'));
            }
        }

        // If ticket is closed, don't change status, otherwise maybe mark as in-progress if admin comments
        if ($ticket->status === 'open' && Auth::user() && (Auth::user()->isAdmin() || Auth::user()->isSupport())) {
            $ticket->update(['status' => 'in-progress', 'attended_to_by' => Auth::id()]);
        }

        return back()->with('success', 'Comment added successfully.');
    }

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
                ->with(['attendant', 'category'])
                ->get();
        }

        return Inertia::render('CheckStatus/index', [
            'tickets'           => $tickets,
            'categories'        => Category::all(),
            'searchedReference' => $request->reference,
        ]);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['user', 'attendant', 'comments.user', 'category']);

        return Inertia::render('Ticket/Show', [
            'ticket'     => $ticket,
            'categories' => Category::all(),
        ]);
    }

    public function activateOrder(Request $request, Ticket $ticket)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSupport()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $activations = $ticket->order_activations ?? [];
        $activations[] = now()->toDateTimeString();

        $ticket->update([
            'order_activations' => $activations
        ]);

        return back()->with('success', 'Order processed and recorded successfully.');
    }
}
