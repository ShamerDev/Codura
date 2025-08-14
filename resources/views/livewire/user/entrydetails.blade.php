<?php

namespace App\Http\Livewire\User;

use Livewire\Volt\Component;
use App\Models\Entry;

new class extends Component {
    public $entry;

    public function mount()
    {
        $id = request()->query('id'); // Get the ?id=5 from the URL
        $this->entry = Entry::with(['category', 'images', 'skills'])
            ->where('id', $id)
            ->where('student_id', auth()->id())
            ->firstOrFail();
    }
};
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $entry->title }}</h1>
            <p class="text-gray-600">{{ $entry->description }}</p>
        </div>

        <!-- Project Details -->
        <div class="space-y-8">
            <!-- Category and Semester -->
            <div class="flex items-center justify-between">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md">
                    {{ $entry->category->name ?? 'Uncategorized' }}
                </span>
                <span
                    class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium">{{ $entry->semester }}</span>
            </div>

            <!-- Skills -->
            @if ($entry->skills->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Skills & Technologies</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($entry->skills as $skill)
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-mono font-medium rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Thumbnail -->
            @if ($entry->thumbnail_path)
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Thumbnail</h2>
                    <img src="{{ asset('storage/' . $entry->thumbnail_path) }}" alt="{{ $entry->title }}"
                        class="rounded-lg shadow-lg">
                </div>
            @endif

            <!-- Additional Images -->
            @if ($entry->images->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Additional Images</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($entry->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Project Image"
                                class="rounded-lg shadow-md">
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- External Link -->
            @if ($entry->link)
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Project Link</h2>
                    <a href="{{ $entry->link }}" target="_blank"
                        class="text-blue-600 hover:underline">{{ $entry->link }}</a>
                </div>
            @endif
        </div>

        <!-- Update Entry Button -->
        <div class="text-center mt-8">
            <a href="{{ route('user.addentry', $entry->id) }}"
                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-800 focus:ring-4 focus:ring-blue-300 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Update Entry
            </a>
        </div>
    </div>
</div>
