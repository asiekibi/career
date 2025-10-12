<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::where('role', 'user')
            ->with(['userBadges.badge', 'cvs'])
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.cvs', compact('users'));
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