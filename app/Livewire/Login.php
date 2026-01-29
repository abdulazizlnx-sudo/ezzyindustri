<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


#[Layout('components.layouts.auth')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6'
    ];

    protected $messages = [
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password harus diisi.',
        'password.min' => 'Password minimal 6 karakter.'
    ];

    public function login()
    {
        $this->validate();

        Log::info('Login attempt for email: ' . $this->email);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            Log::info('Authentication successful.');
            $user = Auth::user();
            Log::info('User authenticated:', ['id' => $user->id, 'email' => $user->email, 'roles' => $user->getRoleNames()]);
            $user = Auth::user();
            
            // Redirect based on role with correct routes
            if ($user->hasRole('manajerial')) {
                return redirect()->intended('/manajerial/dashboard');
            } elseif ($user->hasRole('karyawan')) {
                return redirect()->intended('/karyawan/dashboard');
            }
        }

        Log::warning('Authentication failed.');
        session()->flash('error', 'Email atau password salah!');
    }

    public function render()
    {
        return view('livewire.login');
    }
}