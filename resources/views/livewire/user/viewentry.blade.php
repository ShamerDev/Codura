<?php

use Livewire\Volt\Component;
use App\Models\Entry;

new class extends Component {
    public $entries;

    public function mount()
    {
        // Fetch all entries
        $this->entries = Entry::with('category', 'images', 'skills')->get();
    }
}; ?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-white/90 backdrop-blur-xl border-b border-slate-200/50 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-slate-900 mb-3">
                    Software Engineering Portfolio
                </h1>
                <p class="text-slate-600 text-lg">Building the future, one project at a time</p>
                @if ($entries->isNotEmpty())
                    <div
                        class="mt-6 inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ $entries->count() }} Projects</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        @if ($entries->isNotEmpty())
            <!-- Entries Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($entries as $entry)
                    <div
                        class="group bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/50 hover:scale-[1.02] hover:-translate-y-2">
                        <!-- Image Container -->
                        <div class="relative overflow-hidden">
                            @if ($entry->thumbnail_path)
                                <div class="aspect-video bg-gradient-to-br from-slate-200 to-slate-300 relative">
                                    <img src="{{ asset('storage/' . $entry->thumbnail_path) }}"
                                        alt="{{ $entry->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <!-- Code overlay effect -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                </div>
                            @else
                                <div
                                    class="aspect-video bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center relative">
                                    <div class="text-center text-slate-600">
                                        <div
                                            class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium">Code Preview</span>
                                    </div>
                                </div>
                            @endif

                            <!-- Professional overlay -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <a href="#"
                                        class="inline-flex items-center px-4 py-2 bg-white/95 backdrop-blur-sm text-slate-700 rounded-lg hover:bg-white transition-all font-medium text-sm shadow-xl hover:shadow-2xl transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                        View Project
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Category and Semester -->
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md">
                                    {{ $entry->category->name ?? 'Project' }}
                                </span>
                                <span
                                    class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium">{{ $entry->semester }}</span>
                            </div>

                            <!-- Title -->
                            <h3
                                class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $entry->title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-3">
                                {{ $entry->description }}
                            </p>

                            <!-- Skills with tech-focused styling -->
                            @if ($entry->skills->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mb-5">
                                    @php
                                        $techColors = [
                                            'bg-blue-50 text-blue-700 border border-blue-200',
                                            'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                            'bg-purple-50 text-purple-700 border border-purple-200',
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                            'bg-cyan-50 text-cyan-700 border border-cyan-200',
                                        ];
                                    @endphp
                                    @foreach ($entry->skills->take(4) as $index => $skill)
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-mono font-medium rounded-md {{ $techColors[$index % count($techColors)] }} hover:scale-105 transition-all">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach

                                    @if ($entry->skills->count() > 6)
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium bg-slate-50 text-slate-600 rounded-md border border-slate-200">
                                            +{{ $entry->skills->count() - 6 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Professional Empty State -->
            <div class="text-center py-20">
                <div class="max-w-lg mx-auto">
                    <div class="relative mb-8">
                        <div
                            class="w-32 h-32 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center shadow-2xl">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <!-- Floating code elements -->
                        <div
                            class="absolute -top-4 -left-4 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shadow-lg animate-bounce">
                            <span class="text-blue-600 font-mono text-xs font-bold">{}</span>
                        </div>
                        <div
                            class="absolute -top-2 -right-6 w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center shadow-lg animate-pulse">
                            <span class="text-indigo-600 font-mono text-xs font-bold">[]</span>
                        </div>
                        <div class="absolute -bottom-6 -left-2 w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center shadow-lg animate-bounce"
                            style="animation-delay: 0.5s;">
                            <span class="text-purple-600 font-mono text-sm font-bold">&lt;/&gt;</span>
                        </div>
                    </div>

                    <h3 class="text-3xl font-bold text-slate-900 mb-4">
                        Ready to Build Something Amazing?
                    </h3>
                    <p class="text-slate-600 mb-8 text-lg leading-relaxed">
                        Your portfolio is waiting for your first project. Start showcasing your coding skills and
                        innovative solutions.
                    </p>

                    <a href="#"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Project
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Subtle tech-themed decorative elements -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden opacity-30">
        <div class="absolute top-20 left-10 w-2 h-2 bg-blue-400 rounded-sm animate-ping"></div>
        <div class="absolute top-1/3 right-20 w-3 h-3 bg-indigo-400 rotate-45 animate-pulse"></div>
        <div class="absolute bottom-1/3 left-1/4 w-2 h-2 bg-purple-400 rounded-sm animate-bounce"></div>
        <div class="absolute bottom-20 right-1/4 w-3 h-3 bg-blue-400 rotate-45 animate-pulse"
            style="animation-delay: 1.5s;"></div>
    </div>
</div>
