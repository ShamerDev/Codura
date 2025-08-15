<!-- sbert.blade.php -->

<?php

use Livewire\Volt\Component;
use App\Models\Skill;
use Illuminate\Support\Facades\Http;

new class extends Component {
    public $description = '';
    public $allSkills = [];
    public $suggestedSkills = [];
    public $isGenerating = false;
    public $suggestedSkillsData = [];

    // Add initial description property that can be passed from parent
    public $initialDescription = '';

    public function mount($description = '')
    {
        // Load all skills for processing
        $this->allSkills = Skill::orderBy('name')
            ->get()
            ->map(
                fn($skill) => [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ],
            )
            ->toArray();

        // Set initial description if provided by parent
        if (!empty($description)) {
            $this->description = $description;
        }
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

            // Dispatch event to parent component with the suggestions
            $this->dispatch('skills-generated', $this->suggestedSkills);

            // Show success message
            session()->flash('sbert-success', count($this->suggestedSkills) . ' relevant skills suggested and automatically applied!');
        } catch (\Exception $e) {
            $this->addError('generation', 'Connection error: ' . $e->getMessage());
        } finally {
            $this->isGenerating = false;
        }
    }

    // Listen for description updates from parent
    #[\Livewire\Attributes\On('update-description')]
    public function updateDescription($description)
    {
        $this->description = $description;
        // Clear previous suggestions when description changes
        $this->suggestedSkills = [];

        // For debugging
        // session()->flash('sbert-debug', 'Description updated: ' . substr($description, 0, 20) . '...');
    }
}; ?>

<div class="space-y-4">
    <!-- Description Input Field -->
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Project Description
        </label>
        <textarea wire:model="description" id="description" rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-vertical"
            placeholder="Describe your project, experience, or skills you want to match. For example: 'I built a web application using React and Node.js with MongoDB database'"></textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Generate Skills Button -->
    <div class="group">
        <button type="button" wire:click="generateSkillTags" wire:loading.attr="disabled"
            wire:target="generateSkillTags"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">

            <!-- Loading spinner -->
            <div wire:loading wire:target="generateSkillTags" class="mr-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <!-- Lightning icon when not loading -->
            <svg wire:loading.remove wire:target="generateSkillTags" class="w-5 h-5 mr-2" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
            </svg>

            <span wire:loading.remove wire:target="generateSkillTags">Generate Skill Tags</span>
            <span wire:loading wire:target="generateSkillTags">AI is thinking...</span>
        </button>

        <p class="text-sm text-gray-500 mt-2">
            AI will analyze your description and suggest relevant skills
        </p>
    </div>

    <!-- Error Messages -->
    @error('generation')
        <div class="p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                {{ $message }}
            </p>
        </div>
    @enderror

    <!-- Success Message -->
    @if (session('sbert-success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('sbert-success') }}
            </p>
        </div>
    @endif

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
