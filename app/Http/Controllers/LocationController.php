<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * get cities
     */
    public function getCities(): JsonResponse
    {
        $cities = Location::where('parent_id', 0)
            ->orderBy('location')
            ->get(['id', 'location', 'city_id']);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * get districts
     */
    public function getDistricts(Request $request): JsonResponse
    {
        $request->validate([
            'city_id' => 'required|integer'
        ]);

        $districts = Location::where('parent_id', 1)
            ->where('city_id', $request->city_id)
            ->orderBy('location')
            ->get(['id', 'location', 'city_id']);

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }
}