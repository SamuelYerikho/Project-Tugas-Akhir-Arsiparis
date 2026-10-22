<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $archiveQuery = fn () => Archive::query()
            ->when($user->cannot('view all archives'), function ($q) use ($user) {
                $q->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhereHas('accessRequests', function ($sub) use ($user) {
                            $sub->where('user_id', $user->id)
                                ->where('status', 'approved');
                        });
                });
            });

        $stats = [
            'archives'   => $archiveQuery()->count(),
            'categories' => $archiveQuery()->whereNotNull('category')->where('category', '!=', '')->distinct()->count('category'),
            'reports'    => Report::count(),
            'users'      => User::count(),
        ];

        // Semua arsip tampil lewat pagination, 5 data per halaman
        $recentArchives = $archiveQuery()
            ->with('user')
            ->latest()
            ->paginate(2, ['*'], 'page')
            ->withQueryString();

        return view('dashboard.index', compact('stats', 'recentArchives'));
    }
}