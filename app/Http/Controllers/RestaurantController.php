<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;


class RestaurantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'description' => 'nullable|string|max:1000',
            'address' => 'nullable|string'
        ]);

        $user  = $request->user();

        ///check user role
        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admin can create restaurant'
            ], 403);
        }

        ///check if user already has a restaurant
        if ($user->restaurant) {
            return response()->json([
                'message' => 'You already have a restaurant'
            ], 403);
        }

        $restaurant = Restaurant::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'user_id' => $user->id
        ]);

        return response()->json($restaurant);
    }

    public function me(Request $request)
    {
        $myRestaurant = $request->user()->restaurant;

        return response()->json($myRestaurant);
    }
}
