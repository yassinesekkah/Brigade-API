<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plat;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    public function store(Request $request)
    {
        // validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

       
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // create plat
        $plat = Plat::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $category->id,
            'user_id' => $request->user()->id
        ]);

        return response()->json($plat, 201);
    }


    public function index(Request $request)
    {
        $plats = $request->user()
                        ->plats()
                        ->with('category')
                        ->latest()
                        ->get();

        return response()->json($plats);
    }


    public function platsByCategory(Category $category, Request $request)
    {
        if($category->user_id !== $request->user()->id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $plats = $category->plats()->get();

        return response()->json($plats);
    }
}
