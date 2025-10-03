<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $badges = Badge::all();
        return response()->json([
            'success' => true,
            'data' => $badges
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'badge_name' => 'required|string|max:255',
            'point' => 'required|integer|min:0',
            'badge_icon_url' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $badge = Badge::create([
            'badge_name' => $request->badge_name,
            'point' => $request->point,
            'badge_icon_url' => $request->badge_icon_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Badge created successfully',
            'data' => $badge
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => 'Badge not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $badge
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => 'Badge not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'badge_name' => 'sometimes|string|max:255',
            'point' => 'sometimes|integer|min:0',
            'badge_icon_url' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $badge->update($request->only(['badge_name', 'point', 'badge_icon_url']));

        return response()->json([
            'success' => true,
            'message' => 'Badge updated successfully',
            'data' => $badge
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'success' => false,
                'message' => 'Badge not found'
            ], 404);
        }

        $badge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Badge deleted successfully'
        ]);
    }
}
