<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * CRUD operations for frequently asked questions in the admin panel.
 */
class FaqController extends Controller
{
    /** Display all FAQs ordered by sort position. */
    public function index()
    {
        return Inertia::render('Admin/Faqs', [
            'faqs' => Faq::orderBy('order')->get()
        ]);
    }

    /** Create a new FAQ entry. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
            'question' => 'required|string',
        ]);

        Faq::create($validated);

        return back()->with('success', 'FAQ created successfully.');
    }

    /** Update an existing FAQ entry. */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'answer'   => 'required|string',
            'order'    => 'nullable|integer',
            'question' => 'required|string',
        ]);

        $faq->update($validated);

        return back()->with('success', 'FAQ updated successfully.');
    }

    /** Delete an FAQ entry. */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted successfully.');
    }
}
