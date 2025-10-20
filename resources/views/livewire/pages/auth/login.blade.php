<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // Check if the user is admin or regular user using Spatie Permission
        if (auth()->user()->hasRole('admin')) {
            // Redirect to the admin dashboard
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        } else {
            // Redirect to the user dashboard
            $this->redirectIntended(default: route('user.dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Header -->
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Sign in to your account</h2>
        <p class="mt-2 text-sm text-gray-600">
            Or
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors"
                wire:navigate>
                create a new account
            </a>
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email address
            </label>
            <input wire:model="form.email" id="email" name="email" type="email" autocomplete="email" required
                autofocus
                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm"
                placeholder="Enter your email">
            @error('form.email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Password
            </label>
            <input wire:model="form.password" id="password" name="password" type="password"
                autocomplete="current-password" required
                class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm"
                placeholder="Enter your password">
            @error('form.password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input wire:model="form.remember" id="remember" name="remember" type="checkbox"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition-colors">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}"
                        class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors" wire:navigate>
                        Forgot password?
                    </a>
                </div>
            @endif
        </div>

        <!-- Sign In Button -->
        <div>
            <button type="submit"
                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
                Sign in
            </button>
        </div>
    </form>
</div>
