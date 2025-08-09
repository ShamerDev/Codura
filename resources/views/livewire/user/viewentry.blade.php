<!-- filepath: c:\laravelProjects\laragon\www\seekai\resources\views\livewire\user\viewentry.blade.php -->
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

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Portfolio Entries</h1>
            <p class="text-gray-600">Explore all portfolio projects</p>
        </div>

        <!-- Entries Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($entries as $entry)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Thumbnail -->
                    @if ($entry->thumbnail_path)
                        <img src="{{ asset('storage/' . $entry->thumbnail_path) }}" alt="{{ $entry->title }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                            No Image
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800">{{ $entry->title }}</h2>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($entry->description, 100) }}</p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{ $entry->category->name ?? 'Uncategorized' }}</span>
                            <a href="#" class="text-blue-500 text-sm font-medium hover:underline">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Entries Message -->
        @if ($entries->isEmpty())
            <div class="text-center text-gray-500 mt-12">
                <p>No portfolio entries found.</p>
            </div>
        @endif
    </div>
</div>
