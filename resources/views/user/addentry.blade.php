<x-app-layout>
    <!-- Enhanced Background with Animated Gradient -->
    <div class="min-h-screen relative overflow-hidden">
        <!-- Dynamic Background Elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100"></div>

        <!-- Animated Background Shapes -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-32 w-80 h-80 bg-gradient-to-br from-blue-400/10 to-purple-600/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-40 -left-32 w-96 h-96 bg-gradient-to-br from-indigo-400/10 to-pink-600/10 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
            <div class="absolute bottom-0 right-1/3 w-64 h-64 bg-gradient-to-br from-purple-400/10 to-cyan-600/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
        </div>

        <!-- Floating Elements for Visual Interest -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Icons -->
            <div class="absolute top-20 left-1/4 animate-float">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg opacity-20 transform rotate-12"></div>
            </div>
            <div class="absolute top-40 right-1/4 animate-float animation-delay-1000">
                <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-blue-600 rounded-full shadow-lg opacity-20"></div>
            </div>
            <div class="absolute bottom-40 left-1/3 animate-float animation-delay-2000">
                <div class="w-10 h-10 bg-gradient-to-r from-pink-500 to-red-600 rounded-lg shadow-lg opacity-20 transform -rotate-12"></div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="relative z-10">
            <!-- Page Header with Enhanced Typography -->
            <div class="pt-8 pb-4">
                <div class="max-w-4xl mx-auto px-6 text-center">
                    <!-- Enhanced Page Title -->
                    <div class="relative">
                        <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4 animate-fade-in">
                            ✨ Create Your Portfolio Entry
                        </h1>
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed animate-fade-in animation-delay-300">
                            Showcase your projects, skills, and experiences with our AI-driven portfolio builder
                        </p>

                        <!-- Decorative Line -->
                        <div class="flex justify-center mt-6 animate-fade-in animation-delay-500">
                            <div class="h-1 w-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content with Enhanced Container -->
            <div class="relative">
                <livewire:user.addentry>
            </div>
        </div>

        <!-- Enhanced Floating Action Button (Optional) -->
        <div class="fixed bottom-8 right-8 z-50">
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="group bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <svg class="w-6 h-6 group-hover:-translate-y-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Enhanced Dialogs and Notifications -->
    <x-wui-dialog z-index="z-50" blur="lg" align="center" />
    <x-notifications position="top-end" />

    <!-- Custom Animations CSS -->
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .animation-delay-300 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animation-delay-500 {
            animation-delay: 0.5s;
            opacity: 0;
        }

        .animation-delay-1000 {
            animation-delay: 1s;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        /* Enhanced scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #2563eb, #7c3aed);
        }
    </style>
</x-app-layout>
