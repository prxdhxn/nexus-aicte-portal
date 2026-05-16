<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class PreferenceController extends Controller
{
    public function toggleViewMode(Request $request)
    {
        // Get the current cookie value, default to 'list'
        $currentMode = Cookie::get('curricula_view_mode', 'list');
        
        // Toggle between 'list' and 'card'
        $newMode = $currentMode === 'list' ? 'card' : 'list';
        
        // Create the cookie for 30 days (43200 minutes)
        $cookie = Cookie::make('curricula_view_mode', $newMode, 43200);

        // Alternatively, to forget a cookie: Cookie::forget('curricula_view_mode')
        
        return back()->withCookie($cookie)->with('success', 'View preference saved!');
    }

    public function switchLanguage(Request $request)
    {
        $request->validate(['lang' => 'required|in:en,hi']);
        session(['app_locale' => $request->lang]);
        return back();
    }
}
