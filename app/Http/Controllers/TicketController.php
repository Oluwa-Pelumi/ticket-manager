<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $tickets = $user->role === 'admin'
            ? Ticket::with(['user', 'attendant', 'comments'])->latest()->get()
            : Ticket::where('user_id', $user->id)->with(['attendant', 'comments'])->latest()->get();

        return Inertia::render('Dashboard/index', [
            'tickets' => $tickets,
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
            'content'  => 'required|string',
            'subject'  => 'required|string|max:255',
            'whatsapp_number' => ['required', 'string', 'regex:/^\+?[1-9]\d{1,14}$/'],
            'images'   => 'nullable|array',
            'images.*' => 'image|max:5120',
            'priority' => 'required|string|in:low,medium,high',
        ], [
            'whatsapp_number.regex' => 'The WhatsApp number must be a valid international phone number (e.g., +2348000000000).'
        ]);

        $user = Auth::user();

        if ($user->whatsapp_number !== $validated['whatsapp_number']) {
            $user->update(['whatsapp_number' => $validated['whatsapp_number']]);
        }

        $ticket = Ticket::create([
            'status'   => 'open',
            'user_id'  => Auth::id(),
            'content'  => $validated['content'],
            'subject'  => $validated['subject'],
            'priority' => $validated['priority'],
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $user      = Auth::user();
            $username  = Str::slug($user->name, '_');
            $folder    = $username . '-' . $user->id;

            foreach ($request->file('images') as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $filename  = $ticket->id . '_' . $index . '_' . time() . '.' . $extension;
                $filepath  = $file->storeAs('tickets/' . $folder, $filename, 'public');
                $imagePaths[] = $filepath;
            }
        }

        $ticket->update([
            'images' => $imagePaths
        ]);

        $user->notify(new TicketNotification(
            subject: 'Ticket Received',
            message: 'Your ticket has been submitted successfully. We will respond shortly.'
        ));

        return redirect()->route('dashboard')->with('success', 'Ticket submitted successfully. You can now track your ticket.');
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
            'subject'  => 'required|string|max:255',
            'content'  => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'images'   => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $updateData = [
            'subject'  => $validated['subject'],
            'content'  => $validated['content'],
            'priority' => $validated['priority'],
        ];

        if ($request->hasFile('images')) {
            $user      = Auth::user();
            $username  = Str::slug($user->name, '_');
            $folder    = $username . '-' . $user->id;
            $imagePaths = $ticket->images ?? [];

            foreach ($request->file('images') as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $filename  = $username . '_' . time() . '_' . $index . '.' . $extension;
                $filepath  = $file->storeAs('tickets/'. $folder, $filename, 'public');
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
        if (!Auth::user()->isAdmin()) {
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
        } else if (Auth::user()->isAdmin() && !$ticket->attended_to_by) {
            // Automatically assign to current admin if not already assigned
            $updateData['attended_to_by'] = Auth::id();
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
    public function bulkDelete(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
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
        if (!Auth::user()->isAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:tickets,id',
            'status' => 'required|string|in:open,in-progress,closed',
        ]);

        Ticket::whereIn('id', $validated['ids'])->update([
            'status'         => $validated['status'],
            'attended_to_by' => Auth::id(),             // Assign to current admin
        ]);

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
            'content'  => 'required|string',
            'images'   => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $user      = Auth::user();
            $username  = Str::slug($user->name, '_');
            $folder    = 'comments/' . $username . '-' . $user->id;

            foreach ($request->file('images') as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '_' . $index . '.' . $extension;
                $filepath  = $file->storeAs($folder, $filename, 'public');
                $imagePaths[] = $filepath;
            }
        }

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'images'  => $imagePaths,
        ]);

        // If an admin replies, notify the ticket owner
        if (Auth::user()->isAdmin() && $ticket->user_id !== Auth::id()) {
            $ticket->user->notify(new TicketNotification(
                subject: 'New Reply to Your Ticket',
                message: 'An admin has replied to your ticket regarding: ' . $ticket->subject . '. Check the dashboard for details.'
            ));

            $ticket->user->notify(new TicketNotification(
                subject: '',
                message: $comment
            ));
        }

        // If ticket is closed, don't change status, otherwise maybe mark as in-progress if admin comments
        if ($ticket->status === 'open' && Auth::user()->isAdmin()) {
            $ticket->update(['status' => 'in-progress', 'attended_to_by' => Auth::id()]);
        }

        return back()->with('success', 'Comment added successfully.');
    }
}
