<?php

use Livewire\Volt\Component;
use App\Models\Entry;
use App\Models\Portfolio;

new class extends Component {
    public $entries = [];
    public $slug;

    public function mount()
    {
        $this->slug = request()->query('slug');

        $portfolio = Portfolio::where('slug', $this->slug)->first();

        if (!$portfolio) {
            $this->entries = [];
            return;
        }

        $this->entries = Entry::where('student_id', $portfolio->user_id)->where('is_public', 1)->with('category')->orderBy('created_at', 'desc')->get();
    }
};
?>

<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Public Portfolio</h1>

        @if ($entries->isEmpty())
            <p class="text-gray-600 text-center">No public entries to display.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($entries as $entry)
                    <div class="bg-gray-50 p-4 rounded-xl shadow-sm flex flex-col">
                        @if ($entry->thumbnail_path)
                            <img src="{{ asset('storage/' . $entry->thumbnail_path) }}"
                                class="w-full h-40 object-cover rounded-lg mb-4" />
                        @endif
                        <h2 class="text-lg font-semibold text-gray-800">{{ $entry->title }}</h2>
                        <p class="text-sm text-gray-600">{{ $entry->category->name ?? 'Uncategorized' }} •
                            {{ $entry->semester }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
