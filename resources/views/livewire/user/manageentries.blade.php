<?php

use Livewire\Volt\Component;
use App\Models\Entry;

new class extends Component {
    public $entries = [];
    public $publicLink;

    public function mount()
    {
        $this->loadEntries();
        $this->setPublicLink();
    }

    public function loadEntries()
    {
        $this->entries = Entry::where('student_id', auth()->id())
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();
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

    public function setPublicLink()
    {
        if (auth()->user()->portfolio) {
            $this->publicLink = route('portfolio.viewpublic', [
                'slug' => auth()->user()->portfolio->slug
            ]);
        } else {
            $this->publicLink = '#'; // fallback if no portfolio yet
        }
    }
};
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-10 px-6">
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-lg p-8">

        <!-- PUBLIC LINK & QR CODE -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Your Public Portfolio</h1>
        <div class="flex flex-col md:flex-row items-center justify-between bg-gray-50 p-6 rounded-xl mb-10 shadow-sm">
            <div class="mb-4 md:mb-0">
                <p class="text-gray-600 mb-2">
                    Share this link with others to view all your public entries:
                </p>
                <a href="{{ $publicLink }}" target="_blank" class="text-blue-600 hover:underline break-words">
                    {{ $publicLink }}
                </a>
            </div>
            <div>
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($publicLink) !!}
            </div>
        </div>

        <!-- MANAGE ENTRIES -->
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Your Entries</h1>
        @if ($entries->isEmpty())
            <p class="text-gray-600 text-center">No entries yet. Create one to get started.</p>
        @else
            <div class="space-y-6">
                @foreach ($entries as $entry)
                    <div
                        class="flex items-center justify-between bg-gray-50 border rounded-xl p-4 shadow-sm hover:shadow-md transition">
                        <!-- Thumbnail -->
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
                            <!-- Title & Meta -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">{{ $entry->title }}</h2>
                                <p class="text-sm text-gray-600">
                                    {{ $entry->category->name ?? 'Uncategorized' }} • {{ $entry->semester }}
                                </p>
                            </div>
                        </div>
                        <!-- Toggle Visibility -->
                        <div class="flex items-center space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only"
                                    wire:click="toggleVisibility({{ $entry->id }})"
                                    {{ $entry->is_public ? 'checked' : '' }}>
                                <div class="w-12 h-6 bg-gray-300 rounded-full shadow-inner relative">
                                    <div
                                        class="dot absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition {{ $entry->is_public ? 'translate-x-6 bg-green-500' : '' }}">
                                    </div>
                                </div>
                            </label>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium {{ $entry->is_public ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $entry->is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
