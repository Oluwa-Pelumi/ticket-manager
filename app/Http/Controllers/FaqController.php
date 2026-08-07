<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

/**
 * CRUD operations for frequently asked questions in the admin panel.
 */
class FaqController extends Controller
{
    /** Display all FAQs ordered by sort position. */
    public function index(Request $request)
    {
        return view('admin.faqs', [
            'faqs'       => Faq::orderBy('order')->get(),
            'editingFaq' => $request->filled('edit')
                ? Faq::find($request->edit)
                : null,
        ]);
    }

    /** Create a new FAQ entry. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'answer'   => 'required|string',
            'question' => 'required|string',
            'order'    => 'nullable|integer',
        ]);

        Faq::create($validated);

        return back()->with('success', 'FAQ created successfully.');
    }

    /** Update an existing FAQ entry. */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'answer'   => 'required|string',
            'question' => 'required|string',
            'order'    => 'nullable|integer',
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /** Delete an FAQ entry. */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted successfully.');
    }
}