<?php

use Livewire\Volt\Component;
use App\Models\Entry;
use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $entries = [];
    public $slug;
    public $portfolio;
    public $selectedEntry = null;
    public $showEntryModal = false;
    public $chartData = [];

    public function mount()
    {
        $this->slug = request()->query('slug');

        $this->portfolio = Portfolio::where('slug', $this->slug)->first();

        if (!$this->portfolio) {
            $this->entries = [];
            return;
        }

        // Load entries with relationships
        $this->entries = Entry::where('student_id', $this->portfolio->user_id)
            ->where('is_public', 1)
            ->with(['category', 'images', 'skills'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate chart data for the portfolio owner
        $this->generateChartData();
    }

    public function generateChartData()
    {
        if (!$this->portfolio) {
            $this->chartData = [];
            return;
        }

        $studentId = $this->portfolio->user_id;
        $maxScore = 0.5;
        $growthRate = 0.3;

        $this->chartData = DB::table('skill_categories')
            ->leftJoin('skill_category_links', 'skill_categories.id', '=', 'skill_category_links.skill_category_id')
            ->leftJoin('skills', 'skill_category_links.skill_id', '=', 'skills.id')
            ->leftJoin('entry_skill_tags', 'skills.id', '=', 'entry_skill_tags.skill_id')
            ->leftJoin('entries', 'entry_skill_tags.entry_id', '=', 'entries.id')
            ->where('entries.student_id', $studentId)
            ->where('entries.is_public', 1) // Only include public entries
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

    public function viewEntry($entryId)
    {
        $this->selectedEntry = $this->entries->find($entryId);
        $this->showEntryModal = true;
    }

    public function closeModal()
    {
        $this->showEntryModal = false;
        $this->selectedEntry = null;

        // Add this line to dispatch an event when modal closes
        $this->dispatch('modal-closed');
    }
};
?>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Portfolio Header -->
        <div class="text-center mb-12">
            <div
                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-bold text-2xl">
                    {{ substr($portfolio->user->name ?? 'S', 0, 1) }}
                </span>
            </div>
            <h1
                class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
                {{ $portfolio->user->name ?? 'Student' }}'s Portfolio
            </h1>
            <p class="text-xl text-gray-600">Showcasing Skills & Creative Projects</p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto mt-6"></div>
        </div>

        <!-- Portfolio Overview Section -->
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Skills Chart -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            Skills Overview
                        </h3>

                        @if (!empty($chartData))
                            <div class="flex justify-center">
                                <div class="relative w-full" style="height: 400px;" x-data="{
                                    chart: null,
                                    initChart() {
                                        if (this.chart) {
                                            this.chart.destroy();
                                        }
                                        this.chart = new Chart($refs.canvas, {
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
                                                    pointBorderWidth: 1,
                                                    pointRadius: 3,
                                                    pointHoverRadius: 5
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
                                                                size: 8
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
                                                                size: 9
                                                            },
                                                            backdropPadding: 3,
                                                            showLabelBackdrop: false
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
                                                                size: 12,
                                                                weight: 'bold'
                                                            },
                                                            padding: 20,
                                                            centerPointLabels: true,
                                                            callback: function(value) {
                                                                // Break long labels into multiple lines
                                                                if (value.length > 10) {
                                                                    return value.match(/.{1,10}/g);
                                                                }
                                                                return value;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }"
                                    x-init="initChart()" @modal-closed.window="setTimeout(() => initChart(), 300)">
                                    <canvas x-ref="canvas"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center">
                                <div class="text-gray-400 mb-2">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-sm">No skill data available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Portfolio Stats and Skills -->
                <div>
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Portfolio Stats</h3>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 text-center border border-blue-100">
                                <div class="text-2xl font-bold text-blue-600">{{ $entries->count() }}</div>
                                <div class="text-sm text-blue-700">Projects</div>
                            </div>
                            <div
                                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 text-center border border-purple-100">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ $entries->flatMap->skills->unique('id')->count() }}</div>
                                <div class="text-sm text-purple-700">Skills</div>
                            </div>
                        </div>

                        @if (!empty($chartData))
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Skills</h3>
                            <div class="space-y-2">
                                @foreach ($chartData as $category => $score)
                                    @if ($score > 0)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-700">{{ $category }}</span>
                                            <div class="flex items-center">
                                                <div class="w-16 h-2 bg-gray-200 rounded-full mr-2">
                                                    <div class="h-full bg-emerald-500 rounded-full"
                                                        style="width: {{ $score * 100 }}%"></div>
                                                </div>
                                                <span
                                                    class="text-gray-500 text-xs">{{ number_format($score * 100, 0) }}%</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Section -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <span
                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2v-10a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </span>
                Projects
                <span
                    class="ml-2 text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ $entries->count() }}</span>
            </h2>

            @if ($entries->isEmpty())
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div
                            class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">No Public Projects Yet</h3>
                        <p class="text-gray-600 leading-relaxed">This portfolio doesn't have any public entries to
                            display at the moment. Check back soon for updates!</p>
                    </div>
                </div>
            @else
                <!-- Projects Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($entries as $entry)
                        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 cursor-pointer transform hover:-translate-y-1"
                            wire:click="viewEntry({{ $entry->id }})">

                            <!-- Thumbnail -->
                            <div class="relative h-48 overflow-hidden">
                                @if ($entry->thumbnail_path)
                                    <img src="{{ asset('storage/' . $entry->thumbnail_path) }}"
                                        alt="{{ $entry->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-blue-100 via-purple-50 to-indigo-100 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2v-10a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <!-- Live Link Indicator -->
                                @if ($entry->link)
                                    <div
                                        class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2"></path>
                                        </svg>
                                        Live
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Categories and Semester -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-50 to-blue-100 text-blue-700 border border-blue-200">
                                        {{ $entry->category->name ?? 'Uncategorized' }}
                                    </span>
                                    @if ($entry->semester)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200">
                                            {{ $entry->semester }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h3
                                    class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $entry->title }}
                                </h3>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                    {{ Str::limit($entry->description, 120) }}
                                </p>

                                <!-- Skills Preview -->
                                @if ($entry->skills->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mb-4">
                                        @foreach ($entry->skills->take(3) as $skill)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs rounded-md bg-gradient-to-r from-purple-50 to-purple-100 text-purple-700 border border-purple-200">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                        @if ($entry->skills->count() > 3)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs rounded-md bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 border border-gray-200">
                                                +{{ $entry->skills->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Action Footer -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <span
                                        class="text-sm font-semibold text-blue-600 group-hover:text-blue-700 flex items-center">
                                        View Details
                                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>

                                    @if ($entry->images->count() > 0)
                                        <div class="flex items-center text-gray-500 text-xs">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $entry->images->count() + 1 }} photos
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Entry Details Modal -->
    @if ($showEntryModal && $selectedEntry)
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 px-8 py-6 relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm border border-white/20">
                                    {{ $selectedEntry->category->name ?? 'Uncategorized' }}
                                </span>
                                @if ($selectedEntry->semester)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-white/15 text-white backdrop-blur-sm">
                                        {{ $selectedEntry->semester }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-3xl font-bold text-white">{{ $selectedEntry->title }}</h3>
                        </div>
                        <button wire:click="closeModal"
                            class="text-white/80 hover:text-white p-2 hover:bg-white/10 rounded-full transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="p-8">
                        <!-- Description -->
                        <div class="mb-8">
                            <p class="text-gray-700 leading-relaxed text-lg">{{ $selectedEntry->description }}</p>
                        </div>

                        <!-- Images Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                            <!-- Main Thumbnail -->
                            @if ($selectedEntry->thumbnail_path)
                                <div class="lg:col-span-2">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Project Preview
                                    </h4>
                                    <img src="{{ asset('storage/' . $selectedEntry->thumbnail_path) }}"
                                        alt="{{ $selectedEntry->title }}"
                                        class="w-full rounded-xl shadow-xl border border-gray-200 max-h-96 object-cover">
                                </div>
                            @endif

                            <!-- Additional Images -->
                            @if ($selectedEntry->images->isNotEmpty())
                                <div class="lg:col-span-2">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-purple-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        Project Gallery
                                        <span
                                            class="ml-2 text-sm bg-purple-100 text-purple-700 px-2 py-1 rounded-full">{{ $selectedEntry->images->count() }}
                                            photos</span>
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($selectedEntry->images as $image)
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                alt="Project Image"
                                                class="w-full h-32 object-cover rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Skills and Link -->
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Skills -->
                            @if ($selectedEntry->skills->isNotEmpty())
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                            </path>
                                        </svg>
                                        Technologies Used
                                        <span
                                            class="ml-2 text-sm bg-amber-100 text-amber-700 px-2 py-1 rounded-full">{{ $selectedEntry->skills->count() }}</span>
                                    </h4>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($selectedEntry->skills as $skill)
                                            <span
                                                class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- External Link -->
                            @if ($selectedEntry->link)
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7h-4M17 7v4">
                                            </path>
                                        </svg>
                                        Live Project
                                    </h4>
                                    <a href="{{ $selectedEntry->link }}" target="_blank"
                                        onclick="event.stopPropagation()"
                                        class="group inline-flex items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:border-green-300 transition-all duration-200 hover:shadow-lg">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7h-4M17 7v4">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex-1">
                                            <p class="font-semibold text-green-800 mb-1">View Live Project</p>
                                            <p class="text-sm text-green-600 break-all">
                                                {{ Str::limit($selectedEntry->link, 50) }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-green-500 group-hover:translate-x-1 transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
