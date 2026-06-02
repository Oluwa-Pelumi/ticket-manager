<?php

 namespace App\Http\Controllers;

 use Inertia\Inertia;
 use App\Models\Category;
 use Illuminate\Support\Str;
 use Illuminate\Http\Request;
 use App\Http\Controllers\Controller;

 class CategoryController extends Controller
 {
     public function index()
     {
         return Inertia::render('Admin/Categories/Index', [
             'categories' => rescue(fn() => Category::all(), []),
         ]);
     }

     public function store(Request $request)
     {
         $validated = $request->validate([
             'name'  => 'required|string|max:255',
             'group' => 'nullable|string|max:255',
         ]);

         $validated['slug'] = Str::slug($validated['name']);

         Category::create($validated);
         
         return back()->with('success', 'Category created successfully.');
     }

     public function update(Request $request, Category $category)
     {
         $validated = $request->validate([
             'name'  => 'required|string|max:255',
             'group' => 'nullable|string|max:255',
         ]);

         $validated['slug'] = Str::slug($validated['name']);

         $category->update($validated);

         return back()->with('success', 'Category updated successfully.');
     }

     public function destroy(Category $category)
     {
         $category->delete();
         return back()->with('success', 'Category deleted successfully.');
     }
 }
