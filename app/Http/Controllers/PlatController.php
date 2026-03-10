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
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

       
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $imagePath = null;

        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('plats', 'public');
        }

        // create plat
        $plat = Plat::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $category->id,
            'user_id' => $request->user()->id,
            'image' => $imagePath
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


    public function show(Request $request, Plat $plat)
    {
        $user = $request->user();

        if($user->id !== $plat->user_id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $plat->load('category');

        return response()->json($plat);
    }


    public function update(Request $request, Plat $plat)
    {
        $user = $request->user();

        if($user->id !== $plat->user_id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:250',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image'
        ]);

        if($request->hasFile('image')){
            $validated['image'] = $request->file('image')
                                            ->store('plats', 'public');
        }

        $plat->update($validated);


        return response()->json($plat);
    }


    public function destroy(Request $request, Plat $plat)
    {
        $user = $request->user();

        if($plat->user_id !== $user->id){
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $plat->delete();

        return response()->json([
            'message' => 'Plat deleted seccussfully'
        ]);
    }
}
