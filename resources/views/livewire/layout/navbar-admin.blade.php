<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public bool $mobileMenuOpen = false;

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }
};
?>

<nav class="w-full bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg">
                    <img src="{{ asset('favicon/code.png') }}" alt="Codura Logo"
                        class="h-6 w-6 filter brightness-0 invert">
                </div>
                <span
                    class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Codura Admin
                </span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="#"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Dashboard
                </a>

                {{-- <a href="#"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Manage Users
                </a> --}}

                {{-- <a href="#"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.entries') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    All Entries
                </a> --}}

                {{-- <a href="#"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.skills') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Skill Tags
                </a> --}}

                {{-- <a href="#"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Settings
                </a> --}}
            </div>

            <!-- User / Logout -->
            <div class="flex items-center space-x-3">
                @auth
                    <button wire:click="logout"
                        class="px-5 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        Logout
                    </button>
                @endauth

                <!-- Mobile Toggle -->
                <button wire:click="toggleMobileMenu"
                    class="md:hidden p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        @if ($mobileMenuOpen)
            <div class="md:hidden border-t border-gray-200 py-4 space-y-2">
                <a href="#"
                    class="block px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Dashboard
                </a>

                {{-- <a href="#"
                    class="block px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.users') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Manage Users
                </a> --}}

                {{-- <a href="#"
                    class="block px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.entries') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    All Entries
                </a> --}}

                {{-- <a href="#"
                    class="block px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.skills') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Skill Tags
                </a> --}}

                {{-- <a href="#"
                    class="block px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg {{ request()->routeIs('admin.settings') ? 'text-indigo-600 bg-indigo-50' : '' }}">
                    Settings
                </a> --}}
            </div>
        @endif
    </div>
</nav>
