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
        $this->resequenceFaqs();
        $faqs = Faq::orderBy('order')->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'faqs' => $faqs
            ]);
        }

        return view('admin.faqs', [
            'faqs'       => $faqs,
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

        $this->resequenceFaqs();

        $requestedOrder = $validated['order'] ?? (Faq::count() + 1);
        $validated['order'] = (int) $requestedOrder;

        $faq = Faq::create($validated);
        $this->resequenceFaqs($faq, (int) $requestedOrder);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully.',
                'faqs'    => Faq::orderBy('order')->get(),
            ]);
        }

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

        $this->resequenceFaqs();

        $requestedOrder = $validated['order'] ?? $faq->order;
        $validated['order'] = (int) $requestedOrder;

        $faq->update($validated);
        $this->resequenceFaqs($faq, (int) $requestedOrder);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully.',
                'faqs'    => Faq::orderBy('order')->get(),
            ]);
        }

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /** Delete an FAQ entry. */
    public function destroy(Request $request, Faq $faq)
    {
        $faq->delete();
        $this->resequenceFaqs();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully.',
                'faqs'    => Faq::orderBy('order')->get(),
            ]);
        }

        return back()->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Re-index all FAQs so their `order` column is strictly sequential (1, 2, 3... N).
     * If $targetFaq is provided, it is placed at $targetOrder before re-indexing.
     */
    private function resequenceFaqs(?Faq $targetFaq = null, ?int $targetOrder = null): void
    {
        $faqs = Faq::where('id', '!=', $targetFaq?->id ?? 0)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        if ($targetFaq) {
            $total = $faqs->count() + 1;
            $targetOrder = max(1, min($targetOrder ?? $total, $total));
            $faqs->splice($targetOrder - 1, 0, [$targetFaq]);
        }

        foreach ($faqs as $index => $faq) {
            $expectedOrder = $index + 1;
            if ($faq->order !== $expectedOrder) {
                $faq->update(['order' => $expectedOrder]);
            }
        }
    }
}