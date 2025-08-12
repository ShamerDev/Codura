<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $chartData = [];

    public function mount(): void
    {
        $studentId = auth()->id();
        $maxScore = 0.5; // Maximum score for a category
        $growthRate = 0.3; // Higher = faster early growth, lower = slower growth

        // Query to get number of distinct entries per skill category for this student
        $this->chartData = DB::table('skill_categories')
            ->leftJoin('skill_category_links', 'skill_categories.id', '=', 'skill_category_links.skill_category_id')
            ->leftJoin('skills', 'skill_category_links.skill_id', '=', 'skills.id')
            ->leftJoin('entry_skill_tags', 'skills.id', '=', 'entry_skill_tags.skill_id')
            ->leftJoin('entries', 'entry_skill_tags.entry_id', '=', 'entries.id')
            ->where('entries.student_id', $studentId)
            ->select(
                'skill_categories.name',
                DB::raw("
                    COALESCE(
                        {$maxScore} * (1 - EXP(-{$growthRate} * COUNT(DISTINCT entries.id))),
                        0
                    ) as normalized_score
                "),
            )
            ->groupBy('skill_categories.name')
            ->pluck('normalized_score', 'skill_categories.name')
            ->toArray();

        // Make sure all categories exist in chartData
        $allCategories = DB::table('skill_categories')->pluck('name')->toArray();
        foreach ($allCategories as $category) {
            if (!isset($this->chartData[$category])) {
                $this->chartData[$category] = 0;
            }
        }
    }
};
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Skill Category Chart</h2>

    <div class="flex justify-start">
        <div style="width: 500px; height: 500px;"> <!-- Custom size -->
            <canvas id="skillChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        new Chart(document.getElementById('skillChart'), {
            type: 'radar',
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    label: 'Skill Growth',
                    data: Object.values(chartData),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // Important to fit container size
                scales: {
                    r: {
                        suggestedMin: 0,
                        suggestedMax: 1
                    }
                }
            }
        });
    </script>
</div>
