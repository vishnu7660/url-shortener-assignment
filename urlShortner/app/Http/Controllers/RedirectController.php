<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function redirect($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        // Increment click counter
        $shortUrl->incrementClicks();

        // Redirect to original URL
        return redirect($shortUrl->original_url);
    }
}
