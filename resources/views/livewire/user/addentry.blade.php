<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Entry;
use App\Models\EntryCategory;
use App\Models\Skill;
use App\Models\EntryImage;
use App\Models\EntrySkillTag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    // Form fields
    public $title;
    public $description;
    public $category_id;
    public $semester;
    public $link;
    public $thumbnail;
    public $images = [];
    public $selectedSkills = [];

    // Data for selects
    public $categories = [];
    public $allSkills = [];
    public $suggestedSkills = [];
    public $isGenerating = false;

    // Update mode properties
    public $entryId = null;
    public $isUpdateMode = false;
    public $existingThumbnail = null;
    public $existingImages = [];

    public function mount()
    {
        // Check if we're in update mode
        $this->entryId = request()->query('id');
        $this->isUpdateMode = !empty($this->entryId);

        // Load categories from DB
        $this->categories = EntryCategory::orderBy('name')->get();

        // Load skills into key/value array - FIXED: Using correct structure
        $this->allSkills = Skill::orderBy('name')
            ->get()
            ->map(
                fn($skill) => [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ],
            )
            ->toArray();

        // If update mode, load existing entry data
        if ($this->isUpdateMode) {
            $this->loadEntryData();
        }
    }

    public function loadEntryData()
    {
        $entry = Entry::with(['category', 'images', 'skills'])
            ->where('id', $this->entryId)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        // Populate form fields with existing data
        $this->title = $entry->title;
        $this->description = $entry->description;
        $this->category_id = $entry->category_id;
        $this->semester = $entry->semester;
        $this->link = $entry->link;

        // Load existing thumbnail
        $this->existingThumbnail = $entry->thumbnail_path;

        // Load existing images
        $this->existingImages = $entry->images->toArray();

        // Load selected skills
        $this->selectedSkills = $entry->skills->pluck('id')->toArray();
    }

    public function generateSkillTags()
    {
        // Validate description is not empty
        if (empty(trim($this->description))) {
            $this->addError('description', 'Please enter a description first');
            return;
        }

        $this->isGenerating = true;
        $this->suggestedSkills = [];
        $this->clearValidation();

        try {
            // Prepare data for FastAPI service
            $requestData = [
                'description' => $this->description,
                'skills' => $this->allSkills,
            ];

            // Call FastAPI service
            $serviceUrl = config('app.sbert_service_url', 'http://localhost:8001');
            $response = Http::timeout(30)->post($serviceUrl . '/generate-skills', $requestData);

            if (!$response->successful()) {
                $this->addError('generation', 'Failed to connect to AI service. Status: ' . $response->status());
                return;
            }

            $result = $response->json();

            if (!$result['success']) {
                $this->addError('generation', $result['error'] ?? 'Unknown error occurred');
                return;
            }

            // Store the suggestions
            $this->suggestedSkills = $result['suggested_skills'];

            // Automatically select suggested skills
            foreach ($this->suggestedSkills as $skill) {
                if (!in_array($skill['id'], $this->selectedSkills)) {
                    $this->selectedSkills[] = $skill['id'];
                }
            }

            // Show success message
            session()->flash('sbert-success', count($this->suggestedSkills) . ' relevant skills suggested and automatically applied!');
        } catch (\Exception $e) {
            $this->addError('generation', 'Connection error: ' . $e->getMessage());
        } finally {
            $this->isGenerating = false;
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:entry_categories,id',
            'semester' => 'nullable|string|max:50',
            'link' => 'nullable|url|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'selectedSkills' => 'array',
        ]);

        if ($this->isUpdateMode) {
            $this->updateEntry();
        } else {
            $this->createEntry();
        }
    }

    public function createEntry()
    {
        // Save main entry
        $entry = Entry::create([
            'student_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'semester' => $this->semester,
            'link' => $this->link,
            'is_public' => true, // Default public
            'thumbnail_path' => $this->thumbnail ? $this->thumbnail->store('thumbnails', 'public') : null,
        ]);

        // Save additional images
        foreach ($this->images as $index => $image) {
            EntryImage::create([
                'entry_id' => $entry->id,
                'image_path' => $image->store('entry_images', 'public'),
                'position' => $index + 1,
            ]);
        }

        // Save skills
        foreach ($this->selectedSkills as $skillId) {
            EntrySkillTag::create([
                'entry_id' => $entry->id,
                'skill_id' => $skillId,
                'confidence_score' => 1.0, // Manual selection always 100%
            ]);
        }

        // Flash success + reset form
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Entry Created',
            'message' => 'Your portfolio entry has been saved successfully.',
        ]);

        $this->reset(['title', 'description', 'category_id', 'semester', 'link', 'thumbnail', 'images', 'selectedSkills', 'suggestedSkills']);
    }

    public function updateEntry()
    {
        $entry = Entry::where('id', $this->entryId)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        // Prepare update data
        $updateData = [
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'semester' => $this->semester,
            'link' => $this->link,
        ];

        // Handle thumbnail update
        if ($this->thumbnail) {
            // Delete old thumbnail if exists
            if ($entry->thumbnail_path && Storage::disk('public')->exists($entry->thumbnail_path)) {
                Storage::disk('public')->delete($entry->thumbnail_path);
            }
            $updateData['thumbnail_path'] = $this->thumbnail->store('thumbnails', 'public');
        }

        // Update entry
        $entry->update($updateData);

        // Handle new images
        if (!empty($this->images)) {
            $maxPosition = $entry->images()->max('position') ?? 0;
            foreach ($this->images as $index => $image) {
                EntryImage::create([
                    'entry_id' => $entry->id,
                    'image_path' => $image->store('entry_images', 'public'),
                    'position' => $maxPosition + $index + 1,
                ]);
            }
        }

        // Update skills - remove old ones and add new ones
        EntrySkillTag::where('entry_id', $entry->id)->delete();
        foreach ($this->selectedSkills as $skillId) {
            EntrySkillTag::create([
                'entry_id' => $entry->id,
                'skill_id' => $skillId,
                'confidence_score' => 1.0, // Manual selection always 100%
            ]);
        }

        // Flash success
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Entry Updated',
            'message' => 'Your portfolio entry has been updated successfully.',
        ]);

        // Reload data to reflect changes
        $this->loadEntryData();
        $this->reset(['thumbnail', 'images']);
    }

    // Add this new listener method
    #[\Livewire\Attributes\On('skills-generated')]
    public function handleSkillsGenerated($suggestedSkills)
    {
        // Clear current selection
        $this->selectedSkills = [];

        // Add suggested skills to selection
        foreach ($suggestedSkills as $skill) {
            if (!in_array($skill['id'], $this->selectedSkills)) {
                $this->selectedSkills[] = $skill['id'];
            }
        }

        // Optional: Show a message about auto-selection
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'Skills Auto-Selected',
            'message' => count($suggestedSkills) . ' relevant skills have been automatically selected for you.',
        ]);
    }

    // Add method to watch description changes and sync with SBERT
    public function updatedDescription()
    {
        // Dispatch event to SBERT component to update its description
        $this->dispatch('update-description', $this->description);
    }
};
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                {{ $isUpdateMode ? 'Update Portfolio Entry' : 'Add Portfolio Entry' }}
            </h1>
        </div>

        <form wire:submit.prevent="save" class="space-y-8">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow">
                <div class="p-8 space-y-6">
                    <!-- Title -->
                    <div class="group">
                        <x-input label="Project Title" placeholder="Enter an engaging title for your project"
                            wire:model.defer="title" />
                    </div>

                    <!-- Project Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Project Description
                        </label>
                        <textarea wire:model="description" id="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-vertical"
                            placeholder="Describe your project, experience, or skills you want to match."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Generate Skills Button -->
                    <div class="group">
                        <button type="button" wire:click="generateSkillTags" wire:loading.attr="disabled"
                            wire:target="generateSkillTags"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg">
                            <span wire:loading.remove wire:target="generateSkillTags">Generate Skill Tags</span>
                            <span wire:loading wire:target="generateSkillTags">AI is thinking...</span>
                        </button>
                        <p class="text-sm text-gray-500 mt-2">
                            AI will analyze your description and suggest relevant skills
                        </p>
                    </div>

                    <!-- Preview of Suggested Skills (Optional) -->
                    @if (!empty($suggestedSkills) && !$isGenerating)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                AI Suggestions Applied:
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach (array_slice($suggestedSkills, 0, 5) as $skill)
                                    <span
                                        class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                        {{ $skill['name'] }}
                                        <span class="ml-1 opacity-75">
                                            ({{ number_format($skill['similarity'] * 100, 0) }}%)
                                        </span>
                                    </span>
                                @endforeach
                                @if (count($suggestedSkills) > 5)
                                    <span class="text-xs text-blue-600">
                                        +{{ count($suggestedSkills) - 5 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- FIXED Skills Selector -->
            <div x-data="{
                search: '',
                selected: @entangle('selectedSkills'),
                allSkills: @js($allSkills),
                dropdownOpen: false,
                get filteredSkills() {
                    return this.allSkills.filter(skill =>
                        this.search === '' || skill.name.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                toggleDropdown() {
                    this.dropdownOpen = !this.dropdownOpen;
                    if (this.dropdownOpen) {
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    }
                },
                selectSkill(skill) {
                    if (!this.selected.includes(skill.id)) {
                        this.selected.push(skill.id);
                    }
                    this.search = '';
                },
                removeSkill(skillId) {
                    this.selected = this.selected.filter(id => id !== skillId);
                },
                getSkillName(skillId) {
                    const skill = this.allSkills.find(s => s.id == skillId);
                    return skill ? skill.name : '';
                }
            }" class="relative">

                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Skills & Technologies
                    <span class="text-gray-500 font-normal ml-1">(Select all that apply)</span>
                </label>

                <!-- Searchable Dropdown -->
                <div class="relative">
                    <div class="relative">
                        <input type="text" x-ref="searchInput" x-model="search" @click="dropdownOpen = true"
                            @focus="dropdownOpen = true" placeholder="🔍 Search or browse skills..."
                            class="w-full px-4 py-3 pr-10 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300" />
                        <button type="button" @click="toggleDropdown()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- FIXED Dropdown List -->
                    <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1" @click.away="dropdownOpen = false"
                        class="absolute top-full left-0 w-full mt-2 bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto z-50">

                        <template x-for="skill in filteredSkills" :key="skill.id">
                            <div @click="selectSkill(skill)"
                                :class="selected.includes(skill.id) ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50'"
                                class="px-4 py-3 cursor-pointer transition-colors duration-200 flex items-center justify-between">
                                <span x-text="skill.name" class="font-medium"></span>
                                <div x-show="selected.includes(skill.id)" class="text-blue-500">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredSkills.length === 0" class="px-4 py-6 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            No skills found matching your search.
                        </div>
                    </div>
                </div>

                <!-- Selected Skills Tags -->
                <div x-show="selected.length > 0" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="mt-4">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="skillId in selected" :key="skillId">
                            <div
                                class="group bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-medium px-4 py-2 rounded-full flex items-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <span x-text="getSkillName(skillId)" class="mr-2"></span>
                                <button type="button" @click="removeSkill(skillId)"
                                    class="ml-1 w-5 h-5 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        <span x-text="selected.length"></span> skill(s) selected
                    </p>
                </div>
            </div>

            <!-- Project Details Card -->
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6 rounded-t-2xl">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.562M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        Project Details
                    </h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- FIXED Category Dropdown -->
                        <div x-data="{
                            dropdownOpen: false,
                            selected: @entangle('category_id'),
                            options: @js($categories),
                            getSelectedName() {
                                const option = this.options.find(opt => opt.id == this.selected);
                                return option ? option.name : 'Choose a category';
                            }
                        }" class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                            <div class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" type="button"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-left">
                                    <span x-text="getSelectedName()"></span>
                                    <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                                        :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    @click.away="dropdownOpen = false"
                                    class="absolute left-0 top-full mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto z-40">

                                    <template x-for="option in options" :key="option.id">
                                        <div @click="selected = option.id; dropdownOpen = false"
                                            :class="selected === option.id ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50'"
                                            class="px-4 py-3 cursor-pointer transition-colors duration-200">
                                            <span x-text="option.name"></span>
                                        </div>
                                    </template>

                                    <div x-show="options.length === 0" class="px-4 py-6 text-center text-gray-500">
                                        No categories found.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FIXED Semester Dropdown -->
                        <div x-data="{
                            dropdownOpen: false,
                            selected: @entangle('semester'),
                            options: ['Semester 1', 'Semester 2', 'Semester 3', 'Semester 4', 'Semester 5', 'Semester 6', 'Semester 7', 'Semester 8']
                        }" class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                            <div class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" type="button"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-left">
                                    <span x-text="selected || 'Select semester'"></span>
                                    <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                                        :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-1"
                                    @click.away="dropdownOpen = false"
                                    class="absolute left-0 top-full mt-2 w-full bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto z-30">

                                    <template x-for="option in options" :key="option">
                                        <div @click="selected = option; dropdownOpen = false"
                                            :class="selected === option ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50'"
                                            class="px-4 py-3 cursor-pointer transition-colors duration-200">
                                            <span x-text="option"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- External Link -->
                    <div>
                        <x-input label="Project Link" placeholder="https://github.com/yourusername/project-name"
                            wire:model.defer="link" type="url"
                            class="transition-all duration-300 focus:scale-[1.01]" />
                    </div>
                </div>
            </div>

            <!-- Media Upload Card -->
            <div
                class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow transition-all duration-300 hover:shadow-xl">
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-8 py-6 rounded-t-2xl">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Visual Assets
                    </h2>
                </div>
                <div class="p-8 space-y-8">
                    <!-- Existing Thumbnail (Update Mode Only) -->
                    @if ($isUpdateMode && $existingThumbnail)
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700">
                                Current Thumbnail
                            </label>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $existingThumbnail) }}" alt="Current thumbnail"
                                    class="w-32 h-32 object-cover rounded-lg shadow-md">
                                <button type="button" wire:click="removeExistingThumbnail"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Thumbnail Upload -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            @if ($isUpdateMode && $existingThumbnail)
                                Replace Thumbnail Image
                            @else
                                Thumbnail Image
                            @endif
                            <span class="text-gray-500 font-normal ml-1">(Main project image)</span>
                        </label>

                        <div
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-purple-400 transition-colors duration-300 bg-gray-50 hover:bg-purple-50">
                            <div class="space-y-4">
                                <div
                                    class="mx-auto w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <label for="thumbnail" class="cursor-pointer">
                                        <span
                                            class="text-lg font-semibold text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                            Click to upload thumbnail
                                        </span>
                                        <input id="thumbnail" type="file" wire:model="thumbnail" class="hidden"
                                            accept="image/*">
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 2MB</p>
                                </div>

                                @if ($thumbnail)
                                    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-700 font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $thumbnail->getClientOriginalName() }} ready to upload
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Existing Images (Update Mode Only) -->
                    @if ($isUpdateMode && !empty($existingImages))
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700">
                                Current Images
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach ($existingImages as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image['image_path']) }}" alt="Existing image"
                                            class="w-full h-24 object-cover rounded-lg shadow-md">
                                        <button type="button" wire:click="removeExistingImage({{ $image['id'] }})"
                                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Additional Images Upload -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            @if ($isUpdateMode)
                                Add More Images
                            @else
                                Additional Images
                            @endif
                            <span class="text-gray-500 font-normal ml-1">(Project screenshots, diagrams, etc.)</span>
                        </label>

                        <div
                            class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-purple-400 transition-colors duration-300 bg-gray-50 hover:bg-purple-50">
                            <div class="space-y-4">
                                <div
                                    class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <label for="images" class="cursor-pointer">
                                        <span
                                            class="text-lg font-semibold text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                            Upload multiple images
                                        </span>
                                        <input id="images" type="file" wire:model="images" multiple
                                            class="hidden" accept="image/*">
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, GIF up to 4MB each</p>
                                </div>

                                @if ($images && count($images) > 0)
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm text-blue-700 font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ count($images) }} image(s) ready to upload
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save/Update Button -->
            <div class="text-center pt-4">
                <x-button wire:click="save" spinner="save"
                    class="px-12 py-4 text-lg font-semibold bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:ring-4 focus:ring-blue-300">
                    @if ($isUpdateMode)
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Update Portfolio Entry
                    @else
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Save Portfolio Entry
                    @endif
                </x-button>
            </div>

            <!-- Back to Details Button (Update Mode Only) -->
            @if ($isUpdateMode)
                <div class="text-center">
                    <a href="{{ route('user.entrydetails', ['id' => $entryId]) }}"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Entry Details
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
