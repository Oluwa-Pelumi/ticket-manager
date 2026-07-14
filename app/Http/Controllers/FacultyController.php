<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    public function index()
    {
        $editingFaculty = null;
        if (request()->has('edit')) {
            $editingFaculty = Faculty::find(request('edit'));
        }

        $faculties = Faculty::withCount('departments')->orderBy('name')->get();
        return view('admin.faculties', compact('faculties', 'editingFaculty'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Faculty::create($data);

        return back()->with('success', 'Faculty created successfully.');
    }

    public function update(Request $request, Faculty $faculty)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $faculty->id,
        ]);

        $data['slug'] = Str::slug($data['name']);

        $faculty->update($data);

        return redirect()->route('admin.structure.index')->with('success', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return back()->with('success', 'Faculty and all its departments deleted.');
    }

    public function structure()
    {
        $editingFaculty = null;
        if (request()->has('edit_faculty')) {
            $editingFaculty = Faculty::find(request('edit_faculty'));
        }

        $editingDepartment = null;
        if (request()->has('edit_department')) {
            $editingDepartment = Department::find(request('edit_department'));
        }

        $faculties = Faculty::withCount('departments')->orderBy('name')->get();
        $departments = Department::with('faculty')->orderBy('name')->get();

        return view('admin.structure', compact('faculties', 'departments', 'editingFaculty', 'editingDepartment'));
    }
}
