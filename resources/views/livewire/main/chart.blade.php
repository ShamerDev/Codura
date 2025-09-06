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
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 p-2 rounded-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Skill Development Radar</h2>
    </div>

    <div class="bg-gradient-to-br from-slate-50 to-gray-100 rounded-xl p-6 border border-gray-200">
        <div class="flex justify-center">
            <div class="relative" style="width: 570px; height: 570px;" x-data x-init="new Chart($refs.canvas, {
                type: 'radar',
                data: {
                    labels: Object.keys(@js($chartData)),
                    datasets: [{
                        label: 'Skill Progression',
                        data: Object.values(@js($chartData)),
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
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
                                    size: 8,
                                    weight: 'bold'
                                },
                                padding: 20
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
