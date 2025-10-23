<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $percentages = [];
    public ?float $average = null;
    public array $strongest = [];
    public array $weakest = [];
    public bool $hasEntries = false;
    public float $maxValue = 0;
    public float $minValue = 0;
    public int $categoriesCount = 0;

    public function mount(): void
    {
        $studentId = auth()->id();
        $maxScore = 0.5;
        $growthRate = 0.3;

        $data = DB::table('skill_categories')
            ->leftJoin('skill_category_links', 'skill_categories.id', '=', 'skill_category_links.skill_category_id')
            ->leftJoin('skills', 'skill_category_links.skill_id', '=', 'skills.id')
            ->leftJoin('entry_skill_tags', 'skills.id', '=', 'entry_skill_tags.skill_id')
            ->leftJoin('entries', 'entry_skill_tags.entry_id', '=', 'entries.id')
            ->where('entries.student_id', $studentId)
            ->select('skill_categories.name', DB::raw("COALESCE({$maxScore} * (1 - EXP(-{$growthRate} * COUNT(DISTINCT entries.id))), 0) as score"))
            ->groupBy('skill_categories.name')
            ->pluck('score', 'skill_categories.name')
            ->toArray();

        $allCategories = DB::table('skill_categories')->pluck('name')->toArray();
        $this->categoriesCount = count($allCategories);

        // Determine if user has any entries at all
        $entryCount = DB::table('entries')->where('student_id', $studentId)->count();
        $this->hasEntries = $entryCount > 0;

        foreach ($allCategories as $category) {
            // store as percentage 0-100
            $this->percentages[$category] = isset($data[$category]) ? round($data[$category] * 100, 2) : 0.0;
        }

        if ($this->categoriesCount > 0) {
            $this->maxValue = max($this->percentages);
            $this->minValue = min($this->percentages);

            // If there are no entries, keep average as null to indicate "No data"
            $this->average = $this->hasEntries ? round(array_sum($this->percentages) / $this->categoriesCount, 2) : null;

            // Find all categories that tie for strongest / weakest
            $this->strongest = array_keys($this->percentages, $this->maxValue);
            $this->weakest = array_keys($this->percentages, $this->minValue);
        }
    }
};
?>

<div class="p-8 space-y-8">
    <div class="bg-white p-2 rounded-lg">
    </div>
    <h2 class="text-3xl font-bold text-white tracking-tight mb-2">Skill Performance Overview</h2>
    <p class="text-white text-sm mb-6">A visual breakdown of your current skill distribution across all categories.</p>

    <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg p-8 space-y-8 border border-white/10">

        <!-- Progress bars -->
        <div class="space-y-5">
            @foreach ($percentages as $category => $value)
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="font-medium text-white text-sm">{{ $category }}</span>
                        <span class="text-white text-xs font-semibold">{{ $value }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                        <div class="
                            h-2 rounded-full transition-all duration-700
                            @if ($value >= 70) bg-gradient-to-r from-green-400 to-emerald-500
                            @elseif ($value >= 40) bg-gradient-to-r from-yellow-400 to-amber-500
                            @else bg-gradient-to-r from-rose-400 to-red-500 @endif
                        "
                            style="width: {{ $value }}%;"></div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary cards -->
        <div class="grid grid-cols-3 gap-6 text-center pt-4 border-t border-white/10">
            <div class="bg-white rounded-xl py-6 transition">
                <h3 class="text-4xl font-bold text-indigo-400">
                    {{ $average !== null ? $average . '%' : 'No data' }}
                </h3>
                <p class="text-xs uppercase tracking-wide text-indigo-800 mt-1">Overall Skill Average</p>
            </div>

            <div class="bg-white rounded-xl py-6 transition">
                @if (!$hasEntries)
                    <h3 class="text-lg font-bold text-green-400">No data</h3>
                    <p class="text-xs uppercase tracking-wide text-green-800 mt-1">Strongest Area</p>
                @else
                    <h3 class="text-lg font-bold text-green-400">
                        {{ implode(', ', $strongest) }}
                    </h3>
                    <p class="text-xs uppercase tracking-wide text-green-800 mt-1">
                        Strongest Area — {{ $maxValue }}%
                    </p>
                @endif
            </div>

            <div class="bg-white rounded-xl py-6 transition">
                @if (!$hasEntries)
                    <h3 class="text-lg font-bold text-rose-400">No data</h3>
                    <p class="text-xs uppercase tracking-wide text-rose-800 mt-1">Needs Focus</p>
                @else
                    <h3 class="text-lg font-bold text-rose-400">
                        {{ implode(', ', $weakest) }}
                    </h3>
                    <p class="text-xs uppercase tracking-wide text-rose-800 mt-1">
                        Needs Focus — {{ $minValue }}%
                    </p>
                @endif
            </div>
        </div>
    </div>
    <div class="bg-white p-2 rounded-lg">
    </div>
</div>
