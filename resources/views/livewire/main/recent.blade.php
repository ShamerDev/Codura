<?php

use Livewire\Volt\Component;
use App\Models\Entry;

new class extends Component {
    public $recentEntries;

    public function mount()
    {
        // Fetch the most recent entries for the authenticated user
        $this->recentEntries = Entry::with('category', 'skills')
            ->where('student_id', auth()->id())
            ->latest()
            ->take(3) // Limit to 6 entries for a compact layout
            ->get();
    }
};
?>

<div class="p-6">
    <div class="flex items-center space-x-3 mb-6">
        <div class="bg-white p-2 rounded-lg">
            {{-- <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg> --}}
        </div>
        <h2 class="text-2xl font-bold text-white">Recent Entries</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @if ($recentEntries->isNotEmpty())
            @foreach ($recentEntries as $entry)
                <div
                    class="group border border-gray-200 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50 shadow-sm hover:shadow-md transition-all duration-300 hover:border-blue-300">
                    <!-- Thumbnail -->
                    <div class="relative mb-3">
                        @if ($entry->thumbnail_path)
                            <img src="{{ asset('storage/' . $entry->thumbnail_path) }}" alt="{{ $entry->title }}"
                                class="w-full h-32 rounded-lg object-cover">
                        @else
                            <div
                                class="w-full h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Title and Category -->
                    <div class="mb-2">
                        <h3 class="text-sm font-bold text-gray-800 truncate">{{ $entry->title }}</h3>
                        <span
                            class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded font-medium">{{ $entry->category->name ?? 'Uncategorized' }}</span>
                    </div>

                    <!-- Skills -->
                    @if ($entry->skills->isNotEmpty())
                        <div class="flex flex-wrap gap-1">
                            @foreach ($entry->skills->take(3) as $skill)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-mono font-medium rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                            @if ($entry->skills->count() > 3)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-gray-50 text-gray-600 rounded-md border border-gray-200">
                                    +{{ $entry->skills->count() - 3 }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="col-span-3 text-center py-20">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">No Recent Entries</h3>
                <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                    Start adding projects to showcase your skills and achievements.
                </p>
                <a href="{{ route('user.addentry') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all shadow-xl hover:shadow-2xl transform hover:scale-105">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Your First Entry
                </a>
            </div>
        @endif
    </div>
</div>
