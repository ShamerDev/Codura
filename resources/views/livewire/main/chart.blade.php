<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $chartData = [];

    public function mount(): void
    {
        $studentId = auth()->id();

        // ⚙️ Tunable constants (gentle, realistic progression)
        $growthRate = 0.06; // much slower category progression
        $diversityWeight = 0.1; // subtle influence from unique skills
        $maxScore = 1.0; // theoretical cap (rarely reached)

        $categories = DB::table('skill_categories')->pluck('name', 'id')->toArray();
        $chartData = [];

        foreach ($categories as $categoryId => $categoryName) {
            // Get all skills within this category
            $skills = DB::table('skills')->join('skill_category_links', 'skills.id', '=', 'skill_category_links.skill_id')->where('skill_category_links.skill_category_id', $categoryId)->pluck('skills.id');

            if ($skills->isEmpty()) {
                $chartData[$categoryName] = 0;
                continue;
            }

            // Get all skills used by the student in entries
            $skillData = DB::table('entry_skill_tags')->join('entries', 'entry_skill_tags.entry_id', '=', 'entries.id')->where('entries.student_id', $studentId)->whereIn('entry_skill_tags.skill_id', $skills)->select('entry_skill_tags.skill_id')->get();

            if ($skillData->isEmpty()) {
                $chartData[$categoryName] = 0;
                continue;
            }

            // Count total and unique skill uses
            $usageCounts = array_count_values($skillData->pluck('skill_id')->toArray());
            // Total uses across all skills in a category
            $totalUses = array_sum($usageCounts);
            // Only once per skill, for bonus growth.
            $uniqueSkills = count($usageCounts);
            // Total skills in a category
            $categorySize = count($skills);

            // --- Slower, smoother exponential curve ---
            // Gradual curve that rewards continued use (Repetition of skills used)
            $growth = $maxScore * (1 - exp(-$growthRate * pow($totalUses, 0.85)));

            // --- Mild diversity bonus ---
            // Small bonus for using new skills in the category
            $diversityBonus = $diversityWeight * ($uniqueSkills / $categorySize);

            $finalScore = min($growth + $diversityBonus, $maxScore);
            // store as percentage (0 - 100) with 1 decimal to match insights
            $chartData[$categoryName] = round($finalScore * 100, 1);
        }

        // Ensure unused categories appear
        foreach ($categories as $name) {
            if (!isset($chartData[$name])) {
                $chartData[$name] = 0;
            }
        }

        ksort($chartData);
        $this->chartData = $chartData;
    }
};
?>

<div class="p-6">
    <div class="flex items-center space-x-3 mb-6">
        <h2 class="text-2xl font-bold text-white">Skill Development Radar</h2>
    </div>

    <div class="bg-white rounded-xl p-6 border border-white">
        <div class="flex justify-center">
            <div class="relative" style="width: 570px; height: 570px;" x-data x-init="new Chart($refs.canvas, {
                type: 'radar',
                data: {
                    labels: Object.keys(@js($chartData)),
                    datasets: [{
                        label: 'Skill Growth (Balanced)',
                        data: Object.values(@js($chartData)),
                        backgroundColor: 'rgba(99, 102, 241, 0.3)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: 'rgb(79, 70, 229)',
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: '#374151', font: { size: 9 } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed.r !== undefined ?
                                        context.parsed.r.toFixed(1) :
                                        '0.0';
                                    return `${label}: ${value}%`;
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 100,
                            ticks: {
                                color: '#6B7280',
                                font: { size: 8 }
                            },
                            grid: { color: 'rgba(107, 114, 128, 0.2)' },
                            pointLabels: {
                                color: '#374151',
                                font: { size: 10, weight: 'bold' }
                            }
                        }
                    }
                }
            });">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
