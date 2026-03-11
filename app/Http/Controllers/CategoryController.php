<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{      
    public function index(Request $request)
    {   
        $user = $request->user();
        $categories = $user->categories()
                    ->latest()
                    ->get();

        return response()->json($categories, 201);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'user_id' => $request->user()->id
        ]);

        return response()->json($category, 201);
    }


    public function update(Request $request, Category $category)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:250'
        ]);

        $this->authorize('update', $category);

        $category->update([
            'name' => $validated['name']
        ]);

        return response()->json($category, 200);
    }


    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json([
            'message' => 'Category deleted seccussfully'
        ]);
    }
}
