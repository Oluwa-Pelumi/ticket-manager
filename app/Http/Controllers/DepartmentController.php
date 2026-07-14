<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $editingDepartment = null;
        if (request()->has('edit')) {
            $editingDepartment = Department::find(request('edit'));
        }

        $departments = Department::with('faculty')->orderBy('name')->get();
        $faculties   = Faculty::orderBy('name')->get();
        return view('admin.departments', compact('departments', 'faculties', 'editingDepartment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Department::create($data);

        return back()->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $department->update($data);

        return redirect()->route('admin.structure.index', ['tab' => 'departments'])->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }

    /** API endpoint — returns departments for a faculty as JSON (used by Alpine.js on register page). */
    public function byFaculty(Faculty $faculty)
    {
        return response()->json($faculty->departments()->orderBy('name')->get(['id', 'name', 'slug']));
    }
}
