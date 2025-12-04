<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BadgeController extends Controller
{
    /**
     * Display a listing of badges.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $earnedBadgeIds = $user->badges->pluck('id');

        $badges = Badge::all()->map(function ($badge) use ($user, $earnedBadgeIds) {
            $earned = $earnedBadgeIds->contains($badge->id);
            $userBadge = $earned ? $user->userBadges()->where('badge_id', $badge->id)->first() : null;

            return [
                'id' => $badge->id,
                'name' => $badge->name,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'color' => $badge->color,
                'type' => $badge->type,
                'type_label' => $badge->type_label,
                'requirement_value' => $badge->requirement_value,
                'earned' => $earned,
                'earned_at' => $userBadge?->earned_at,
            ];
        });

        $earnedCount = $earnedBadgeIds->count();
        $totalCount = Badge::count();

        return Inertia::render('Badges/Index', [
            'badges' => $badges,
            'earnedCount' => $earnedCount,
            'totalCount' => $totalCount,
        ]);
    }
}
