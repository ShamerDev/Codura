<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
};
?>

<nav class="w-full bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-md">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">

            <!-- Logo / Name -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="h-10 w-10">
                <span class="text-xl font-bold text-gray-900 dark:text-white">
                    SEEK-AI
                </span>
            </div>

            <!-- Navigation Links -->
            <div class="flex space-x-8">
                <a href="{{ route('dashboard') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Home</a>
                <a href="{{ route('user.addentry') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Add
                    Entry</a>
                <a href="{{ route('user.viewentry') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">View
                    Entries</a>
                <a href="{{ route('user.manageentries') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Manage
                    Entries</a>
            </div>

            <!-- User Actions -->
            <div class="flex items-center space-x-4">
                @auth
                    <span class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium">
                        <a href="{{ route('profile') }}" class="text-white hover:underline">
                            {{ Auth::user()->name }}
                        </a>
                    </span>
                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link>
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </button>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium">Login</a>
                @endauth
            </div>

        </div>
    </div>
</nav>
