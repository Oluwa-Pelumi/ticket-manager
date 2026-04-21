<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $tickets = $user->role === 'admin' 
            ? Ticket::with('user')->latest()->get() 
            : Ticket::where('user_id', $user->id)->latest()->get();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|string|in:low,medium,high',
            'image' => 'nullable|image|max:5120',
        ]);

        $filepath = null;
        if ($request->hasFile('image')) {
            $filepath = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'priority' => $validated['priority'],
            'filename' => $filepath,
            'status' => 'open',
        ]);

        return redirect()->route('dashboard')->with('success', 'Ticket submitted successfully.');
    }

    public function deleteTicket(Request $request)
    {
        // Only admins can delete tickets generally, or owners if you prefer
        // But the user requested Admin/User ACL, so I'll restrict to Admin
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
}
