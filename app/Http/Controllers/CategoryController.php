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


    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:250'
        ]);
        
        $user = $request->user();

        if($category->user_id !== $user->id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $category->update([
            'name' => $validated['name']
        ]);

        return response()->json($category, 200);
    }
}
