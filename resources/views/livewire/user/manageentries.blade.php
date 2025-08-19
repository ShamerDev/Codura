<?php

use Livewire\Volt\Component;
use App\Models\Entry;
use App\Models\Portfolio;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

new class extends Component {
    public $entries = [];
    public $portfolio;

    public function mount()
    {
        $this->loadEntries();
        $this->loadPortfolio();
    }

    public function loadEntries()
    {
        $this->entries = Entry::where('student_id', auth()->id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function loadPortfolio()
    {
        $this->portfolio = Portfolio::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'slug' => uniqid(),
                'public_link' => url('/portfolio/' . uniqid()),
            ],
        );
    }

    public function toggleVisibility($id)
    {
        $entry = Entry::where('id', $id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $entry->is_public = !$entry->is_public;
        $entry->save();

        $this->loadEntries();
    }
};
?>
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg p-8 space-y-10">

        <!-- Portfolio Share Section -->
        <div class="bg-gray-50 border rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Your Public Portfolio</h2>
                <p class="text-gray-600 text-sm mb-3">
                    Share this link to showcase all entries you've set as <span class="font-medium">Public</span>.
                </p>
                <a href="{{ $portfolio->public_link }}" target="_blank"
                    class="text-indigo-600 font-medium hover:underline">
                    {{ $portfolio->public_link }}
                </a>
            </div>

            <!-- QR Code (using SVG backend instead of Imagick) -->
            <div class="flex-shrink-0">
                <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(120)->generate($portfolio->public_link)) }}"
                    alt="QR Code" class="rounded-lg shadow-md border w-32 h-32" />
            </div>
        </div>

        <!-- Manage Entries Section -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Your Entries</h1>

        @if ($entries->isEmpty())
            <p class="text-gray-600 text-center">No entries yet. Create one to get started.</p>
        @else
            <div class="space-y-6">
                @foreach ($entries as $entry)
                    <div
                        class="flex items-center justify-between bg-gray-50 border rounded-xl p-4 shadow-sm hover:shadow-md transition">

                        <!-- Thumbnail + Info -->
                        <div class="flex items-center space-x-4">
                            @if ($entry->thumbnail_path)
                                <img src="{{ asset('storage/' . $entry->thumbnail_path) }}"
                                    class="w-16 h-16 rounded-lg object-cover shadow-md border" />
                            @else
                                <div
                                    class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            @endif

                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $entry->title }}</h2>
                                <p class="text-sm text-gray-600">
                                    {{ $entry->category->name ?? 'Uncategorized' }} • {{ $entry->semester }}
                                </p>
                            </div>
                        </div>

                        <!-- Toggle (using Tailwind peer feature for better toggle behavior) -->
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                    wire:click="toggleVisibility({{ $entry->id }})"
                                    {{ $entry->is_public ? 'checked' : '' }}>
                                <div
                                    class="relative w-12 h-6 bg-gray-300 peer-checked:bg-green-500 rounded-full transition-colors">
                                    <div
                                        class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-6">
                                    </div>
                                </div>
                            </label>

                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $entry->is_public ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $entry->is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
