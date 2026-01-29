<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // SuperAdmin sees all short URLs from all companies
        if ($user->isSuperAdmin()) {
            $shortUrls = ShortUrl::with(['user', 'company'])
                ->latest()
                ->paginate(15);
        }
        // Admin sees all URLs from their company
        elseif ($user->isAdmin()) {
            $shortUrls = ShortUrl::with(['user'])
                ->where('company_id', $user->company_id)
                ->latest()
                ->paginate(15);
        }
        // Member sees only their own URLs
        else {
            $shortUrls = ShortUrl::where('user_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        return view('short-urls.index', compact('shortUrls'));
    }

    public function create()
    {
        // Only Admin, Member, Sales, Manager can create URLs
        if (!auth()->user()->canCreateShortUrls()) {
            abort(403, 'You are not authorized to create short URLs.');
        }

        return view('short-urls.create');
    }

    public function store(Request $request)
    {
        // Only Admin, Member, Sales, Manager can create URLs
        if (!auth()->user()->canCreateShortUrls()) {
            abort(403, 'You are not authorized to create short URLs.');
        }

        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        $shortCode = $this->generateUniqueShortCode();

        $shortUrl = ShortUrl::create([
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->company_id,
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
        ]);

        return redirect()
            ->route('short-urls.index')
            ->with('success', 'Short URL created successfully!');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        $user = auth()->user();

        // SuperAdmin can delete any URL
        if ($user->isSuperAdmin()) {
            $shortUrl->delete();
            return redirect()
                ->route('short-urls.index')
                ->with('success', 'Short URL deleted successfully!');
        }

        // Admin can delete URLs from their company
        if ($user->isAdmin() && $shortUrl->company_id === $user->company_id) {
            $shortUrl->delete();
            return redirect()
                ->route('short-urls.index')
                ->with('success', 'Short URL deleted successfully!');
        }

        // Member can only delete their own URLs
        if ($shortUrl->user_id === $user->id) {
            $shortUrl->delete();
            return redirect()
                ->route('short-urls.index')
                ->with('success', 'Short URL deleted successfully!');
        }

        abort(403, 'You are not authorized to delete this short URL.');
    }

    private function generateUniqueShortCode(int $length = 6): string
    {
        do {
            $shortCode = Str::random($length);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        return $shortCode;
    }
}
