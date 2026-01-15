<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nik' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:50'
        ]);

        // Get Customer role
        $customerRole = Role::where('name', 'Customer')->first();
        
        if (!$customerRole) {
            return back()->with('error', 'Customer role not found. Please contact administrator.');
        }

        $user = User::create([
            'role_id' => $customerRole->id,
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'nik' => $validated['nik'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'],
            'phone' => $validated['phone'],
            'status' => 'active'
        ]);

        Auth::login($user);

        return redirect()->route('customer.home')
            ->with('success', 'Registration successful! Welcome to DistroZone.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKasir()) {
            return redirect()->route('cashier.dashboard');
        } else {
            return redirect()->route('customer.home');
        }
    }
}