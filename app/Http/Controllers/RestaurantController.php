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

        ///check if user already has a restaurant
        $user  = $request->user();
       
        if($user->restaurant){
            return response()->json([
                'message' => 'You already have a restaurant'
            ], 403);
        }

        $restaurant = Restaurant::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'address' => $validated['address'],
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
