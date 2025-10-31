<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Debug: Check kya data aa raha hai
            \Log::info('Google User Data:', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'id' => $googleUser->getId()
            ]);
            
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Agar existing user hai toh google_id aur email_verified_at update karo
                $user->google_id = $googleUser->getId();
                $user->email_verified_at = $user->email_verified_at ?: now();   // Force email verify
                $user->save();
                
                \Log::info('Existing user updated:', ['user_id' => $user->id, 'email_verified' => $user->email_verified_at]);
                
                // Check karo agar banned toh nahi hai
                if ($user->is_banned) {
                    return redirect()->route('login')->with('error', 'Your account has been banned.');
                }
                
                Auth::login($user);
            } else {
                // Naya user create karo
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null,
                    'role' => 'user',
                    'email_verified_at' => now(), // Google verified emails ko automatically verify
                ]);

                \Log::info('New user created:', ['user_id' => $newUser->id]);
                Auth::login($newUser);
            }

            return redirect('/dashboard');

        } catch (\Exception $e) {
            \Log::error('Google login error:', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('error', 'Google authentication failed.');
        }
    }
}