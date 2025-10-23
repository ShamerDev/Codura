<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Codura - Software Engineering Student Growth Tracking and E-Portfolio System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center">
                        <span class="text-white font-mono font-bold text-sm">&lt;/&gt;</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Codura</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}"
                        class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-24 pb-16 md:py-32">
        {{-- Background Element --}}
        <div
            class="absolute inset-0 -z-10 bg-[radial-gradient(50%_50%_at_50%_50%,rgba(124,118,245,0.13)_0%,rgba(255,255,255,0)_100%)]">
        </div>
        <div class="absolute top-1/4 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -z-10"></div>

        <div class="container px-4 md:px-6">
            <div class="grid gap-6 lg:grid-cols-[1fr_500px] lg:gap-12 xl:grid-cols-[1fr_550px]">
                <div x-data="{ loaded: false }" x-init="$nextTick(() => setTimeout(() => loaded = true, 100))"
                    :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                    class="flex flex-col justify-center space-y-4 transition-all duration-500 delay-100">
                    <div class="space-y-2">
                        <div
                            class="inline-block rounded-full bg-secondary px-3 py-1 text-sm font-medium text-secondary-foreground animate-fade-in">
                            Powered by AI
                        </div>
                        <h1
                            class="text-3xl font-bold tracking-tighter sm:text-4xl md:text-5xl lg:text-6xl font-display">
                            Showcase Your Skills with
                            <span
                                class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-500">Intelligence</span>
                        </h1>
                        <p
                            class="max-w-[600px] text-muted-foreground md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed">
                            Transform your academic journey into a compelling portfolio. Codura uses advanced AI to
                            automatically identify and categorize your skills.
                        </p>
                    </div>
                    <div class="flex flex-col gap-2 min-[400px]:flex-row">
                        @auth
                            <a href="{{ route('user.dashboard') }}"
                                class="inline-flex h-12 items-center justify-center rounded-full bg-primary px-6 font-medium text-primary-foreground shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                                Get Started Free
                                <x-icon name="arrow-right" class="ml-2 h-4 w-4" solid />
                            </a>
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex h-12 items-center justify-center rounded-full bg-primary px-6 font-medium text-primary-foreground shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                                Get Started Free
                                <x-icon name="arrow-right" class="ml-2 h-4 w-4" solid />
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex h-12 items-center justify-center rounded-full bg-white border border-gray-200 px-6 font-medium text-gray-900 shadow transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                                View Portfolios
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
    </section>

    {{-- Feature Section --}}
    <section class="py-16 md:py-24 bg-secondary/50">
        <div class="container px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-block rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary mb-4">
                    Key Features
                </div>
                <h2 class="text-3xl font-bold tracking-tight md:text-4xl mb-4 font-display">
                    Powered by Advanced AI
                </h2>
                <p class="text-muted-foreground md:text-xl">
                    Our intelligent system analyzes your work and automatically creates comprehensive skill profiles,
                    saving you time while showcasing your expertise.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 max-w-5xl mx-auto">
                <div class="neo-morphism rounded-2xl p-6 transition-all duration-300 hover:translate-y-[-4px] animate-slide-up"
                    style="animation-delay: 100ms">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">AI-Powered Skill Tagging</h3>
                    <p class="text-muted-foreground">Advanced SBERT models automatically analyze your projects and
                        identify technical and soft skills with high accuracy.</p>
                </div>
                <div class="neo-morphism rounded-2xl p-6 transition-all duration-300 hover:translate-y-[-4px] animate-slide-up"
                    style="animation-delay: 200ms">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Interactive Radar Charts</h3>
                    <p class="text-muted-foreground">Visualize your skill distribution across categories with
                        beautiful, interactive radar charts that tell your story at a glance.</p>
                </div>
                <div class="neo-morphism rounded-2xl p-6 transition-all duration-300 hover:translate-y-[-4px] animate-slide-up"
                    style="animation-delay: 300ms">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Rich Project Showcase</h3>
                    <p class="text-muted-foreground">Upload images, videos, and documents to create compelling project
                        presentations that highlight your achievements.</p>
                </div>
                <div class="neo-morphism rounded-2xl p-6 transition-all duration-300 hover:translate-y-[-4px] animate-slide-up"
                    style="animation-delay: 400ms">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Shareable Portfolio Links</h3>
                    <p class="text-muted-foreground">Generate beautiful, public portfolio URLs to share with employers,
                        professors, or anyone interested in your work.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-16 md:py-24 relative overflow-hidden">
        <div
            class="absolute inset-0 -z-10 bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_110%)]">
        </div>

        <div class="container px-4 md:px-6">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-block rounded-full bg-primary/10 px-3 py-1 text-sm font-medium text-primary mb-4">
                    Simple Process
                </div>
                <h2 class="text-3xl font-bold tracking-tight md:text-4xl mb-4 font-display">
                    How Codura Works
                </h2>
                <p class="text-muted-foreground md:text-xl">
                    Our intelligent portfolio creation system makes it easy to showcase your skills effectively.
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-3 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-indigo-300 rounded-xl p-6 shadow-sm neo-morphism min-h-40">
                        <h3 class="text-xl font-bold mb-2">Upload Your Work</h3>
                        <p class="text-muted-foreground">
                            Upload your projects, assignments, and other academic work to create your portfolio.
                        </p>
                    </div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-indigo-300 rounded-xl p-6 shadow-sm neo-morphism min-h-40">
                        <h3 class="text-xl font-bold mb-2">AI Analyzes Skills</h3>
                        <p class="text-muted-foreground">
                            Our AI automatically extracts and categorizes your skills from the uploaded content.
                        </p>
                    </div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-indigo-300 rounded-xl p-6 shadow-sm neo-morphism min-h-40">
                        <h3 class="text-xl font-bold mb-2">Share Your Portfolio</h3>
                        <p class="text-muted-foreground">
                            Get a beautiful, shareable portfolio that highlights your unique skill set and projects.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <div class="mt-12 text-center">
                <a href="{{ route('user.dashboard') }}"
                    class="inline-flex h-12 items-center justify-center rounded-lg bg-primary px-8 font-medium text-white shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                    Get Started
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @else
            <div class="mt-12 text-center">
                <a href="{{ route('register') }}"
                    class="inline-flex h-12 items-center justify-center rounded-lg bg-primary px-8 font-medium text-white shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                    Get Started
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        @endauth
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-b from-background to-secondary/50">
        <div class="container px-4 md:px-6">
            <div class="max-w-3xl mx-auto text-center">
                <svg class="h-12 w-12 text-primary mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                <h2 class="text-3xl font-bold tracking-tight md:text-4xl mb-4 font-display">
                    Ready to Transform Your Portfolio?
                </h2>
                <p class="text-muted-foreground md:text-xl mb-8">
                    Join thousands of students who have already elevated their career prospects with AI-powered skill
                    showcasing.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                        <a href="{{ route('user.dashboard') }}"
                            class="inline-flex h-12 items-center justify-center rounded-lg bg-primary px-8 font-medium text-sm text-white shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                            Start Building Your Portfolio
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="inline-flex h-12 items-center justify-center rounded-lg bg-primary px-8 font-medium text-sm text-black shadow transition-all hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary">
                            Start Building Your Portfolio
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-flex h-12 items-center justify-center rounded-lg bg-white border border-gray-200 px-8 font-medium text-sm text-gray-900 shadow transition-all hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                            Browse Sample Portfolios
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</body>

</html>
