<?php

use Livewire\Volt\Component;
use App\Models\Skill;

new class extends Component
{
    public $description = '';
    public $allSkills = [];
    public $suggestedSkills = [];
    public $isGenerating = false;

    public function mount()
    {
        // Load all skills for processing
        $this->allSkills = Skill::orderBy('name')
            ->get()
            ->map(fn($skill) => [
                'id' => $skill->id,
                'name' => $skill->name,
            ])
            ->toArray();
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
            // Prepare data for Python script
            $inputData = [
                'description' => $this->description,
                'skills' => $this->allSkills
            ];

            // Call Python script
            $process = new \Symfony\Component\Process\Process([
                'python3',
                storage_path('app/scripts/skill_matcher.py'),
                json_encode($inputData)
            ]);

            $process->setTimeout(30);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->addError('generation', 'Failed to generate skill suggestions. Error: ' . $process->getErrorOutput());
                return;
            }

            $output = $process->getOutput();
            $result = json_decode($output, true);

            if (!$result || isset($result['error'])) {
                $this->addError('generation', $result['error'] ?? 'Unknown error occurred');
                return;
            }

            // Store the suggestions
            $this->suggestedSkills = $result['suggested_skills'];

            // Dispatch event to parent component with the suggestions
            $this->dispatch('skills-generated', $this->suggestedSkills);

            // Show success message
            session()->flash('sbert-success', count($this->suggestedSkills) . ' relevant skills have been suggested based on your description.');

        } catch (\Exception $e) {
            $this->addError('generation', 'An error occurred: ' . $e->getMessage());
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
    }
}; ?>

<div class="space-y-4">
    <!-- Generate Skills Button -->
    <div class="group">
        <button type="button"
                wire:click="generateSkillTags"
                wire:loading.attr="disabled"
                wire:target="generateSkillTags"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg font-medium transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">

            <!-- Loading spinner -->
            <div wire:loading wire:target="generateSkillTags" class="mr-2">
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Lightning icon when not loading -->
            <svg wire:loading.remove wire:target="generateSkillTags" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>

            <span wire:loading.remove wire:target="generateSkillTags">Generate Skill Tags</span>
            <span wire:loading wire:target="generateSkillTags">Generating...</span>
        </button>

        <p class="text-sm text-gray-500 mt-2">
            AI will analyze your description and suggest relevant skills
        </p>
    </div>

    <!-- Error Messages -->
    @error('description')
        <div class="p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-600">{{ $message }}</p>
        </div>
    @enderror

    @error('generation')
        <div class="p-3 bg-red-50 border border-red-200 rounded-md">
            <p class="text-sm text-red-600">{{ $message }}</p>
        </div>
    @enderror

    <!-- Success Message -->
    @if(session('sbert-success'))
        <div class="p-3 bg-green-50 border border-green-200 rounded-md">
            <p class="text-sm text-green-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
                {{ session('sbert-success') }}
            </p>
        </div>
    @endif

    <!-- Preview of Suggested Skills (Optional) -->
    @if(!empty($suggestedSkills) && !$isGenerating)
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h4 class="text-sm font-semibold text-blue-800 mb-2">
                AI Suggestions Preview:
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach(array_slice($suggestedSkills, 0, 5) as $skill)
                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                        {{ $skill['name'] }}
                        <span class="ml-1 opacity-75">
                            ({{ number_format($skill['similarity'] * 100, 0) }}%)
                        </span>
                    </span>
                @endforeach
                @if(count($suggestedSkills) > 5)
                    <span class="text-xs text-blue-600">
                        +{{ count($suggestedSkills) - 5 }} more
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>
