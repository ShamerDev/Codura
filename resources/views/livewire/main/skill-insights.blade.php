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

        // === MATCHES RADAR CONFIG ===
        $growthRate = 0.06; // same as radar
        $diversityWeight = 0.1; // same as radar
        $maxScore = 1.0; // same as radar
        $power = 0.85; // same exponent used in radar

        // Fetch categories
        $categories = DB::table('skill_categories')->pluck('name', 'id')->toArray();
        $this->categoriesCount = count($categories);

        // Did the user create any entries?
        $entryCount = DB::table('entries')->where('student_id', $studentId)->count();
        $this->hasEntries = $entryCount > 0;

        // Compute per-category score using the same logic as the radar
        $computed = [];

        foreach ($categories as $categoryId => $categoryName) {
            // get skills for this category
            $skills = DB::table('skills')->join('skill_category_links', 'skills.id', '=', 'skill_category_links.skill_id')->where('skill_category_links.skill_category_id', $categoryId)->pluck('skills.id')->toArray();

            if (empty($skills)) {
                $computed[$categoryName] = 0.0;
                continue;
            }

            // get usage rows for this student limited to these skills
            $skillRows = DB::table('entry_skill_tags')->join('entries', 'entry_skill_tags.entry_id', '=', 'entries.id')->where('entries.student_id', $studentId)->whereIn('entry_skill_tags.skill_id', $skills)->select('entry_skill_tags.skill_id')->get();

            if ($skillRows->isEmpty()) {
                $computed[$categoryName] = 0.0;
                continue;
            }

            // counts
            $usageCounts = array_count_values($skillRows->pluck('skill_id')->toArray());
            $totalUses = array_sum($usageCounts);
            $uniqueSkills = count($usageCounts);
            $categorySize = count($skills);

            // growth curve (same as radar)
            $growth = $maxScore * (1 - exp(-$growthRate * pow($totalUses ?: 0, $power)));

            // mild diversity bonus (same as radar)
            $diversityBonus = $categorySize > 0 ? $diversityWeight * ($uniqueSkills / $categorySize) : 0;

            $finalScore = min($growth + $diversityBonus, $maxScore);

            // store as percentage (0-100) with 1 decimal to match radar
            $computed[$categoryName] = round($finalScore * 100, 1);
        }

        // Ensure all categories exist (even unused)
        foreach ($categories as $name) {
            if (!isset($computed[$name])) {
                $computed[$name] = 0.0;
            }
        }

        // Keep same ordering
        ksort($computed);
        $this->percentages = $computed;

        // Compute insights from the exact same percentages
        if ($this->categoriesCount > 0) {
            $this->maxValue = max($this->percentages);
            $this->minValue = min($this->percentages);

            $this->average = $this->hasEntries ? round(array_sum($this->percentages) / $this->categoriesCount, 1) : null;

            $this->strongest = $this->maxValue > 0 ? array_keys($this->percentages, $this->maxValue) : [];
            $this->weakest = $this->minValue > 0 ? array_keys($this->percentages, $this->minValue) : [];
        }
    }
};
?>


<div class="p-8 space-y-8">
    <h2 class="text-3xl font-bold text-white tracking-tight mb-2">Skill Performance Overview</h2>
    <p class="text-white text-sm mb-6">A visual breakdown of your current skill distribution across all categories.</p>

    <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg p-8 space-y-8 border border-white/10">

        <!-- Progress Bars -->
        <div class="space-y-5">
            @foreach ($percentages as $category => $value)
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="font-medium text-white text-sm">{{ $category }}</span>
                        <span class="text-white text-xs font-semibold">{{ $value }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-700
                                @if ($value >= 70) bg-gradient-to-r from-green-400 to-emerald-500
                                @elseif ($value >= 40) bg-gradient-to-r from-yellow-400 to-amber-500
                                @else bg-gradient-to-r from-rose-400 to-red-500 @endif"
                            style="width: {{ $value }}%;">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary Insights -->
        <div class="grid grid-cols-3 gap-6 text-center pt-4 border-t border-white/10">
            <!-- Average -->
            <div class="bg-white rounded-xl py-6 transition">
                <h3 class="text-4xl font-bold text-indigo-400">
                    {{ $average !== null ? $average . '%' : 'No data' }}
                </h3>
                <p class="text-xs uppercase tracking-wide text-indigo-800 mt-1">Overall Skill Average</p>
            </div>

            <!-- Strongest -->
            <div class="bg-white rounded-xl py-6 transition">
                @if (!$hasEntries)
                    <h3 class="text-lg font-bold text-green-400">No data</h3>
                    <p class="text-xs uppercase tracking-wide text-green-800 mt-1">Strongest Area</p>
                @else
                    <h3 class="text-lg font-bold text-green-400">
                        {{ implode(', ', $strongest) }}
                    </h3>
                    <p class="text-xs uppercase tracking-wide text-green-800 mt-1">
                        Strongest — {{ $maxValue }}%
                    </p>
                @endif
            </div>

            <!-- Weakest -->
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
</div>
