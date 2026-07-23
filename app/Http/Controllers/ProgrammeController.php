<?php

namespace App\Http\Controllers;

use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of all programmes and optional edit form.
     */
    public function index(Request $request)
    {
        $programmes = Programme::withCount('users')->orderBy('name')->get();
        $editingProgramme = null;

        if ($request->has('edit')) {
            $editingProgramme = Programme::find($request->query('edit'));
        }

        return view('admin.programmes', compact('programmes', 'editingProgramme'));
    }

    /**
     * Store a newly created programme in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:programmes,name',
        ]);

        Programme::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme created successfully.');
    }

    /**
     * Update the specified programme in storage.
     */
    public function update(Request $request, Programme $programme)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:programmes,name,' . $programme->id,
        ]);

        $programme->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.programmes.index')->with('success', 'Programme updated successfully.');
    }

    /**
     * Remove the specified programme from storage.
     */
    public function destroy(Programme $programme)
    {
        $programme->delete();

        return redirect()->route('admin.programmes.index')->with('success', 'Programme deleted successfully.');
    }
}
