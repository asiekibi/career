<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\UserBadge;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $badges = Badge::all();
        return view('admin.badges', compact('badges'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'badge_name' => 'required|string|max:255',
            'point' => 'required|integer|min:1',
            'badge_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
        ], [
            'badge_name.required' => 'Rozet adı gereklidir.',
            'point.required' => 'Rozet puanı gereklidir.',
            'point.integer' => 'Rozet puanı sayı olmalıdır.',
            'point.min' => 'Rozet puanı en az 1 olmalıdır.',
            'badge_icon.image' => 'Dosya resim formatında olmalıdır.',
            'badge_icon.mimes' => 'Dosya formatı jpeg, png, jpg veya gif olmalıdır.',
            'badge_icon.max' => 'Dosya boyutu 10MB\'dan küçük olmalıdır.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $badge = new Badge();
        $badge->badge_name = $request->badge_name;
        $badge->point = $request->point;

        // İkon yükleme
        if ($request->hasFile('badge_icon')) {
            $file = $request->file('badge_icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('badge_icons'), $filename);
            $badge->badge_icon_url = 'badge_icons/' . $filename;
        }

        $badge->save();

        return redirect()->route('admin.badges')->with('success', 'Rozet başarıyla eklendi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $id = $request->input('id');
        $badge = Badge::find($id);

        if (!$badge) {
            return back()->with('error', 'Rozet bulunamadı.');
        }

        // İkon dosyasını sil
        if ($badge->badge_icon_url && file_exists(public_path($badge->badge_icon_url))) {
            unlink(public_path($badge->badge_icon_url));
        }

        $badge->delete();

        return redirect()->route('admin.badges')->with('success', 'Rozet başarıyla silindi!');
    }

    /**
     * badge assign form
     */
    public function getAssignBadge($id)
    {
        $user = User::findOrFail($id);
        $badges = Badge::all();
        $userBadges = $user->userBadges()->with('badge')->get();
        return view('admin.add-badge', compact('user', 'badges', 'userBadges'));
    }

    /**
     * store assign badge
     */
    public function storeAssignBadge(Request $request, $id)
    {
        $request->validate([
            'badge_id' => 'required|exists:badges,id',
        ]);

        $user = User::findOrFail($id);
        
        // Aynı rozet zaten atanmış mı kontrol et
        $existingBadge = $user->userBadges()->where('badge_id', $request->badge_id)->first();
        
        if ($existingBadge) {
            return redirect()->back()->with('error', 'Bu rozet zaten atanmış!');
        }
        
        // Badge'in puanını al
        $badge = Badge::findOrFail($request->badge_id);
        $badgePoints = $badge->point;
        
        // Rozeti ata
        $user->userBadges()->create([
            'badge_id' => $request->badge_id,
        ]);

        // Kullanıcının point değerini güncelle
        $user->point = $user->point + $badgePoints;
        $user->save();

        return redirect()->back()->with('success', 'Rozet başarıyla atandı!');
    }

    /**
     * remove badge
     */
    public function removeBadge(Request $request)
    {
        $userBadgeId = $request->input('id');
        $userBadge = UserBadge::findOrFail($userBadgeId);
        
        // user point update
        $user = $userBadge->user;
        $badge = $userBadge->badge;
        $user->point = $user->point - $badge->point;
        $user->save();
        
        $userBadge->delete();

        return redirect()->back()->with('success', 'Rozet başarıyla kaldırıldı!');
    }
    
}