<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect($provider)
    {
        // Add basic validation for supported providers
        if (!in_array($provider, ['github', 'google'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists
            // MongoDB makes this extremely easy
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // If the user doesn't exist, create a new one!
                $user = User::create([
                    'name'        => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Nexus User',
                    'email'       => $socialUser->getEmail(),
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    // Assign a random secure password since they use OAuth
                    'password'    => bcrypt(Str::random(24)),
                    // Social sign-ups are institutes (same as email self-registration)
                    'role'        => 'institute',
                ]);

            } else {
                // If user exists but is linking a new provider, update their record
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            }

            // Log them in natively
            Auth::login($user, true);

            // Redirect to their dashboard
            $routeName = $user->role === 'admin' ? 'admin.dashboard' : ($user->role === 'sme' ? 'sme.dashboard' : 'institute.dashboard');
            return redirect()->route($routeName)->with('show_welcome', true)->with('success', 'Successfully logged in with '.ucfirst($provider).'!');
        } catch (\Exception $e) {
            // If the user rejects the popup or an error occurs
            return redirect()->route('login')->withErrors(['oauth_error' => 'Authentication failed or was canceled. Please try again or use your email.']);
        }
    }
}
