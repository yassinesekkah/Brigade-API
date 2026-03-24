<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ingredient_ids' => 'nullable|array',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ]);

        // check if category belongs to the user's restaurant
        $category = Category::where('id', $validated['category_id'])
            ->where('restaurant_id', $request->user()->restaurant->id)
            ->first();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        // handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plats', 'public');
        }

        // create plat
        $plat = Plat::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $category->id,
            'image' => $imagePath
        ]);

        if (isset($validated['ingredient_ids'])) {
            $plat->ingredients()->sync($validated['ingredient_ids']);
        }

        $plat->load(['category', 'ingredients']);

        return response()->json([
            'message' => 'Plat created successfully',
            'data' => $plat
        ], 201);
    }


    public function index(Request $request)
    {
        $this->authorize('viewAny', Plat::class);

        $user = $request->user();



        $plats = Plat::whereHas('category', function ($query) use ($user) {
            $query->where('restaurant_id', $user->restaurant->id);
        })
            ->with(['category', 'ingredients'])
            ->latest()
            ->get();

        return response()->json($plats);
    }


    public function platsByCategory(Category $category)
    {
        $this->authorize('view', $category);

        $plats = $category->plats()->with('ingredients')->get();

        return response()->json($plats);
    }


    public function show(Plat $plat)
    {
        $this->authorize('update', $plat);

        $plat->load(['category', 'ingredients']);

        return response()->json($plat);
    }


    public function update(Request $request, Plat $plat)
    {
        $this->authorize('update', $plat);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:250',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image',
            'ingredient_ids' => 'nullable|array',
            'ingredient_ids.*' => 'exists:ingredients,id',
        ]);

        // check category ownership
        if (isset($validated['category_id'])) {
            $category = Category::where('id', $validated['category_id'])
                ->where('restaurant_id', $request->user()->restaurant->id)
                ->first();

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found or not authorized'
                ], 404);
            }
        }

        $ingredientIds = $validated['ingredient_ids'] ?? null;
        unset($validated['ingredient_ids']);

        // handle image
        if ($request->hasFile('image')) {
            if ($plat->image) {
                Storage::disk('public')->delete($plat->image);
            }

            $validated['image'] = $request->file('image')->store('plats', 'public');
        }

        $plat->update($validated);

        // sync ingredients
        if ($request->has('ingredient_ids')) {
            $plat->ingredients()->sync($ingredientIds ?? []);
        }

        $plat->load(['category', 'ingredients']);

        return response()->json($plat);
    }

    public function destroy(Plat $plat)
    {
        $this->authorize('delete', $plat);

        $plat->delete();

        return response()->json([
            'message' => 'Plat deleted seccussfully'
        ]);
    }
}
