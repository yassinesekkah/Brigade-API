<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{      
    public function index(Request $request)
    {   
        $user = $request->user();
        
        if(!$user->restaurant){
            return response()->json([
                'message' => 'You must have a restaurant first'
            ], 403);
        }

        $categories = $user->restaurant->categories()
                    ->latest()
                    ->paginate(10);

        return response()->json($categories, 200);
    }


    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,restaurant_id,' . $user->restaurant->id
        ]);

        ///check if is admin
        if(!$user->isAdmin()){
            return response()->json([
                'message' => 'Only admin can create categories'
            ], 403);
        }

        //check if has a restaurant
        if(!$user->restaurant){
            return response()->json([
                'message' => 'You must create a restaurant first'
            ], 403);
        }

        $category = Category::create([
            'name' => $validated['name'],
            'restaurant_id' => $user->restaurant->id
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
