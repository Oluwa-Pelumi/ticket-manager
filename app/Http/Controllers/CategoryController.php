<?php

 namespace App\Http\Controllers;

 use App\Models\Category;
 use Illuminate\Support\Str;
 use Illuminate\Http\Request;
 use App\Http\Controllers\Controller;

 /**
  * CRUD operations for ticket categories in the admin panel.
  */
 class CategoryController extends Controller
 {
     /** Display all categories. */
     public function index()
     {
         $editingCategory = null;
         if (request()->has('edit')) {
             $editingCategory = Category::find(request('edit'));
         }

         return view('admin.categories', [
             'editingCategory' => $editingCategory,
             'categories'      => rescue(fn() => Category::all(), []),
         ]);
     }

     /** Create a new category from validated input. */
     public function store(Request $request)
     {
         $validated = $request->validate([
             'name'  => 'required|string|max:255',
         ]);

         $validated['slug'] = Str::slug($validated['name']);

         Category::create($validated);
         
         return back()->with('success', 'Category created successfully.');
     }

     /** Update an existing category. */
     public function update(Request $request, Category $category)
     {
         $validated = $request->validate([
             'name'  => 'required|string|max:255',
         ]);

         $validated['slug'] = Str::slug($validated['name']);

         $category->update($validated);

         return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
     }

     /** Delete a category. */
     public function destroy(Category $category)
     {
         $category->delete();
         return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
     }
 }