<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Codura - Software Engineering Student Growth Tracking and E-Portfolio System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
        }

        .hero-gradient {
            background: radial-gradient(circle at 30% 20%, rgba(102, 126, 234, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(118, 75, 162, 0.08) 0%, transparent 50%);
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-30px) rotate(2deg);
            }
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 1s ease-out forwards;
        }

        .animate-scale-in {
            animation: scale-in 0.8s ease-out forwards;
        }

        .feature-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }

        .parallax-float {
            animation: float-slow 8s ease-in-out infinite;
        }

        .text-balance {
            text-wrap: balance;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 5px;
        }
    </style>
</head>

<body class="bg-white text-gray-900 overflow-x-hidden">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-effect border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-sm">&lt;/&gt;</span>
                    </div>
                    <span class="text-xl font-semibold tracking-tight text-gray-900">Codura</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#features"
                        class="hidden md:block text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Features</a>
                    <a href="#how-it-works"
                        class="hidden md:block text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">How
                        it Works</a>
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 px-5 py-2 rounded-full transition-all">Sign
                        Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center hero-gradient overflow-hidden">
        <!-- Floating Elements -->
        <div
            class="absolute top-20 right-10 w-72 h-72 bg-gradient-to-br from-indigo-200 to-purple-200 rounded-full opacity-20 blur-3xl parallax-float">
        </div>
        <div class="absolute bottom-20 left-10 w-96 h-96 bg-gradient-to-br from-purple-200 to-indigo-200 rounded-full opacity-20 blur-3xl parallax-float"
            style="animation-delay: -4s;"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="animate-fade-in-up" style="animation-delay: 0.1s; opacity: 0;">
                <div
                    class="inline-flex items-center space-x-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full px-4 py-2 mb-8 shadow-sm">
                    <div class="w-2 h-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full animate-pulse">
                    </div>
                    <span class="text-sm font-medium text-gray-700">Codura</span>
                </div>
            </div>

            <h1 class="animate-fade-in-up text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight mb-6 leading-tight"
                style="animation-delay: 0.2s; opacity: 0;">
                <span class="text-gray-900">Showcase Your</span><br>
                <span class="gradient-text">Skills with Intelligence</span>
            </h1>

            <p class="animate-fade-in-up max-w-2xl mx-auto text-lg md:text-xl text-gray-600 mb-12 leading-relaxed text-balance"
                style="animation-delay: 0.3s; opacity: 0;">
                Transform your academic journey into a compelling portfolio. Codura helps identify and organize your
                skills.
            </p>

            <div class="animate-fade-in-up flex flex-col sm:flex-row items-center justify-center gap-4"
                style="animation-delay: 0.4s; opacity: 0;">
                @auth
                    <a href="{{ route('user.dashboard') }}"
                        class="btn-primary w-full sm:w-auto px-8 py-4 rounded-full text-white font-semibold shadow-xl text-base inline-flex items-center justify-center group">
                        Get Started Free
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="btn-primary w-full sm:w-auto px-8 py-4 rounded-full text-white font-semibold shadow-xl text-base inline-flex items-center justify-center group">
                        Get Started Free
                        <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-4 rounded-full text-gray-900 font-semibold bg-white/80 backdrop-blur-sm border border-gray-200 hover:bg-white hover:shadow-lg transition-all text-base inline-flex items-center justify-center">
                        View Portfolios
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class=" mb-12 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <div class="inline-flex items-center space-x-2 bg-gray-50 rounded-full px-4 py-2 mb-6">
                    <span class="text-sm font-semibold text-gray-700">Key Features</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 text-gray-900">
                    Powered by<br><span class="gradient-text">Advanced AI</span>
                </h2>
                <p class="text-xl text-gray-600 leading-relaxed text-balance">
                    Our intelligent system analyzes your work and automatically creates comprehensive skill profiles,
                    saving you time while showcasing your expertise.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="feature-card bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">AI-Powered Skill Tagging</h3>
                    <p class="text-gray-600 leading-relaxed">Advanced SBERT models automatically analyze your projects
                        and identify technical and soft skills with high accuracy.</p>
                </div>

                <div class="feature-card bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Interactive Radar Charts</h3>
                    <p class="text-gray-600 leading-relaxed">Visualize your skill distribution across categories with
                        beautiful, interactive radar charts that tell your story at a glance.</p>
                </div>

                <div class="feature-card bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Rich Project Showcase</h3>
                    <p class="text-gray-600 leading-relaxed">Upload images, videos, and documents to create compelling
                        project presentations that highlight your achievements.</p>
                </div>

                <div class="feature-card bg-white rounded-3xl p-8 border border-gray-200 shadow-sm">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Shareable Portfolio Links</h3>
                    <p class="text-gray-600 leading-relaxed">Generate beautiful, public portfolio URLs to share with
                        employers, professors, or anyone interested in your work.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section id="how-it-works" class=bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <div
                    class="inline-flex items-center space-x-2 bg-white rounded-full px-4 py-2 mb-6 shadow-sm border border-gray-200">
                    <span class="text-sm font-semibold text-gray-700">Simple Process</span>
                </div>
                <h2 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 text-gray-900">
                    How <span class="gradient-text">Codura</span> Works
                </h2>
                <p class="text-xl text-gray-600 leading-relaxed text-balance">
                    Our intelligent portfolio creation system makes it easy to showcase your skills effectively.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-12 lg:gap-16">
                <div class="relative">
                    <div class="text-center">
                        <div
                            class="inline-flex w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-3xl items-center justify-center mb-8 shadow-xl">
                            <span class="text-3xl font-bold text-white">1</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Upload Your Work</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Upload your projects, assignments, and other academic work to create your portfolio
                            foundation.
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <div class="text-center">
                        <div
                            class="inline-flex w-20 h-20 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-3xl items-center justify-center mb-8 shadow-xl">
                            <span class="text-3xl font-bold text-white">2</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">AI Analyzes Skills</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Our AI automatically extracts and categorizes your skills from the uploaded content with
                            precision.
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <div class="text-center">
                        <div
                            class="inline-flex w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-3xl items-center justify-center mb-8 shadow-xl">
                            <span class="text-3xl font-bold text-white">3</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Share Your Portfolio</h3>
                        <p class="text-gray-600 text-lg leading-relaxed">
                            Get a beautiful, shareable portfolio that highlights your unique skill set and projects
                            instantly.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-white">
        <div class="max-w-4xl mx-auto px-6 mt-32 lg:px-8 text-center">
            <div
                class="inline-flex w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-3xl items-center justify-center mb-8 shadow-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <h2 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 text-gray-900 text-balance">
                Ready to Transform<br>Your <span class="gradient-text">Portfolio?</span>
            </h2>

            <p class="text-xl text-gray-600 mb-12 max-w-2xl mx-auto leading-relaxed text-balance">
                Join thousands of students who have already elevated their career prospects with AI-powered skill
                showcasing.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('user.dashboard') }}"
                        class="btn-primary w-full sm:w-auto px-8 py-4 rounded-full text-white font-semibold shadow-xl text-base inline-flex items-center justify-center">
                        Start Building Your Portfolio
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="btn-primary w-full sm:w-auto px-8 py-4 rounded-full text-white font-semibold shadow-xl text-base inline-flex items-center justify-center">
                        Start Building Your Portfolio
                    </a>
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-4 rounded-full text-gray-900 font-semibold bg-white border-2 border-gray-200 hover:bg-gray-50 hover:shadow-lg transition-all text-base inline-flex items-center justify-center">
                        Browse Sample Portfolios
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center space-x-2 mb-4">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-xs">&lt;/&gt;</span>
                </div>
                <span class="text-lg font-semibold text-gray-900">Codura</span>
            </div>
            <p class="text-gray-600 text-sm">© 2025 Codura. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
