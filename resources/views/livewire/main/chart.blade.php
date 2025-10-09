<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public array $chartData = [];

    public function mount(): void
    {
        $studentId = auth()->id();
        $maxScore = 0.5;
        $growthRate = 0.3;

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
    <div class="flex items-center space-x-3 mb-6">
        <div class="bg-white p-2 rounded-lg">
        </div>
        <h2 class="text-2xl font-bold text-white">Skill Development Radar</h2>
    </div>
    <div class="bg-white rounded-xl p-6 border border-white">
        <div class="flex justify-center">
            <div class="relative" style="width: 570px; height: 570px;" x-data x-init="new Chart($refs.canvas, {
                type: 'radar',
                data: {
                    labels: Object.keys(@js($chartData)),
                    datasets: [{
                        label: 'Skill Progression',
                        data: Object.values(@js($chartData)),
                        backgroundColor: 'rgba(99, 102, 241, 0.3)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 3,
                        pointBackgroundColor: 'rgb(99, 102, 241)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 0,
                        pointRadius: 6,
                        pointHoverRadius: 10,
                        pointHoverBackgroundColor: 'rgb(79, 70, 229)',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 0
                    }]

                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#374151',
                                font: {
                                    family: 'ui-monospace, SFMono-Regular, Consolas, monospace',
                                    size: 9
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 1,
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    family: 'ui-monospace, SFMono-Regular, Consolas, monospace',
                                    size: 8
                                }
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.2)'
                            },
                            angleLines: {
                                color: 'rgba(107, 114, 128, 0.2)'
                            },
                            pointLabels: {
                                color: '#374151',
                                font: {
                                    family: 'ui-monospace, SFMono-Regular, Consolas, monospace',
                                    size: 10,
                                    weight: 'bold'
                                },
                            }
                        }
                    }
                }
            })">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
