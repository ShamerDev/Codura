<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $categories = [];

    public function mount(): void
    {
        // Fetch all skill categories from DB
        $dbCategories = DB::table('skill_categories')->orderBy('name')->get();

        // Map category name => description (hardcoded here)
        $descriptions = [
            'Front-End Development' => 'Focuses on building user-facing interfaces and improving the visual experience with skills like HTML, CSS, JavaScript, React, Vue, and Tailwind.',
            'Back-End Development' => 'Covers server-side logic, databases, APIs, authentication, and frameworks like PHP, Laravel, Node.js, and Python.',
            'Database & Data Handling' => 'Involves designing, querying, and managing databases with tools such as MySQL, PostgreSQL, Firebase, MongoDB, and data transformation processes.',
            'DevOps & Deployment' => 'Includes skills for software deployment and maintenance like Git, Docker, CI/CD pipelines, Linux, AWS, and Vercel.',
            'Professional & Soft Skills' => 'Highlights communication, teamwork, leadership, problem solving, and time management—key for working effectively in teams and projects.',
        ];

        $this->categories = $dbCategories
            ->map(
                fn($cat) => [
                    'name' => $cat->name,
                    'description' => $descriptions[$cat->name] ?? 'No description available.',
                ],
            )
            ->toArray();
    }
};
?>

<div class="p-6">
    <div class="flex items-center space-x-3 mb-6">
        <h2 class="text-2xl font-bold text-white">Tech Stack Overview</h2>
    </div>
    <!-- Scrollable container -->
    <div class="h-[619px] overflow-y-auto border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
        @foreach ($categories as $index => $cat)
            <section
                class="group border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-300">
                <div class="flex items-start space-x-3">
                    <!-- Category Icon -->
                    <div class="flex-shrink-0 mt-1">
                        @if ($cat['name'] === 'Front-End Development')
                            <div class="bg-blue-100 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                        @elseif($cat['name'] === 'Back-End Development')
                            <div class="bg-green-100 p-2 rounded-lg group-hover:bg-green-200 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2">
                                    </path>
                                </svg>
                            </div>
                        @elseif($cat['name'] === 'Database & Data Handling')
                            <div class="bg-yellow-100 p-2 rounded-lg group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                    </path>
                                </svg>
                            </div>
                        @elseif($cat['name'] === 'DevOps & Deployment')
                            <div class="bg-red-100 p-2 rounded-lg group-hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        @else
                            <div class="bg-purple-100 p-2 rounded-lg group-hover:bg-purple-200 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800 font-mono">{{ $cat['name'] }}</h3>
                            <div class="bg-gray-200 px-2 py-0.5 rounded text-xs font-mono text-gray-600">
                                v{{ $index + 1 }}.0
                            </div>
                        </div>
                        <p
                            class="text-gray-700 text-sm leading-relaxed font-mono bg-gray-50 p-3 rounded border-l-2 border-blue-400">
                            <span class="text-blue-600 font-bold">//</span> {{ $cat['description'] }}
                        </p>
                    </div>
                </div>

                <!-- Status indicator -->
                <div class="mt-3 flex justify-end">
                    <div class="flex items-center space-x-1 bg-green-100 px-2 py-1 rounded-full">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs font-mono text-green-700">active</span>
                    </div>
                </div>
            </section>
        @endforeach
    </div>
</div>
