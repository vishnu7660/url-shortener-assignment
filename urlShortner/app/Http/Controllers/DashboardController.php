<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_urls' => 0,
            'total_clicks' => 0,
            'companies_count' => 0,
            'users_count' => 0,
        ];

        // SuperAdmin sees all stats
        if ($user->isSuperAdmin()) {
            $stats['total_urls'] = ShortUrl::count();
            $stats['total_clicks'] = ShortUrl::sum('clicks');
            $stats['companies_count'] = Company::count();
            $stats['users_count'] = User::where('role_id', '!=', $user->role_id)->count();
        }
        // Admin sees company stats
        elseif ($user->isAdmin()) {
            $stats['total_urls'] = ShortUrl::where('company_id', $user->company_id)->count();
            $stats['total_clicks'] = ShortUrl::where('company_id', $user->company_id)->sum('clicks');
            $stats['users_count'] = User::where('company_id', $user->company_id)
                ->where('id', '!=', $user->id)
                ->count();
        }
        // Member sees their own stats
        else {
            $stats['total_urls'] = ShortUrl::where('user_id', $user->id)->count();
            $stats['total_clicks'] = ShortUrl::where('user_id', $user->id)->sum('clicks');
        }

        return view('dashboard', compact('stats'));
    }
}
