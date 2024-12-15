<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('unregistered.login');
    }

    /**
     * Check if email exists in the database.
     */
    public function checkEmail(Request $request)
    {
        Log::info('Test log from controller');
        $email = $request->input('email');
        $exists = User::where('userEmail', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        if (Auth::attempt(['userEmail' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
    
            $user = Auth::user();
            switch ($user->userType) {
                case 'admin':
                    return redirect()->route('admin.home');
                case 'organizer':
                    return redirect()->route('organizer.home');
                case 'member':
                    return redirect()->route('member.home');
                default:
                    Auth::logout(); // Logout if userType is invalid
                    return redirect('/')->with('error', 'Unauthorized role.');
            }
        }
    
        return back()->withErrors(['login' => 'Invalid credentials provided.']);
    }
    
    
    
}
