<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cvs = Cv::with('user','abilities','experiences','educations','languages')->get();
        return response()->json([
            'success' => true,
            'data' => $cvs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'resume' => 'required|string',
            'hobbies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $cv = Cv::create([
            'user_id' => $request->user_id,
            'resume' => $request->resume,
            'hobbies' => $request->hobbies,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'CV created successfully',
            'data' => $cv->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $cv = Cv::with('user','abilities','experiences','educations','languages')->find($id);

        if (!$cv) {
            return response()->json([
                'success' => false,
                'message' => 'CV not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cv
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $cv = Cv::find($id);

        if (!$cv) {
            return response()->json([
                'success' => false,
                'message' => 'CV not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|exists:users,id',
            'resume' => 'sometimes|string',
            'hobbies' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $cv->update($request->only(['user_id', 'resume', 'hobbies']));

        return response()->json([
            'success' => true,
            'message' => 'CV updated successfully',
            'data' => $cv->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $cv = Cv::find($id);

        if (!$cv) {
            return response()->json([
                'success' => false,
                'message' => 'CV not found'
            ], 404);
        }

        $cv->delete();

        return response()->json([
            'success' => true,
            'message' => 'CV deleted successfully'
        ]);
    }
}
