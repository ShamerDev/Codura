<?php

use Livewire\Volt\Component;
use App\Models\Skill;
use App\Models\SkillCategory;
use App\Models\SkillCategoryLink;
use WireUi\Traits\WireUiActions;

new class extends Component {
    use WireUiActions;

    public $tab = 'categories';
    public $activeCategory = null;

    // Skill state
    public $skills = [];
    public $newSkill = '';
    public $newSkillCategories = [];

    // Category state
    public $categories = [];

    // Linking state
    public $selectedSkills = [];
    public $links = [];

    // Confirmation modals
    public $showDeleteCategoryModal = false;
    public $showDeleteSkillModal = false;
    public $showUnlinkSkillModal = false;
    public $categoryToDelete = null;
    public $skillToDelete = null;
    public $skillToUnlink = null;
    public $categoryToUnlinkFrom = null;

    protected $listeners = ['refresh' => 'loadAll'];

    public function mount()
    {
        $this->loadAll();
        $this->setDefaultActiveCategory();
    }

    public function loadAll()
    {
        $this->skills = Skill::orderBy('name')->get();
        $this->categories = SkillCategory::orderBy('name')->get();
        $this->links = SkillCategoryLink::with(['skill', 'category'])->get();

        $this->dispatch('dataUpdated');
    }

    public function setDefaultActiveCategory()
    {
        if ($this->categories->count() > 0 && !$this->activeCategory) {
            $this->activeCategory = $this->categories->first()->id;
        }
    }

    public function setActiveCategory($categoryId)
    {
        $this->activeCategory = $categoryId;
    }

    // ---- Skills ----
    public function addSkill()
    {
        $this->validate([
            'newSkill' => 'required|string|max:255',
            'newSkillCategories' => 'required|array|min:1',
            'newSkillCategories.*' => 'integer|exists:skill_categories,id',
        ]);

        $skill = Skill::firstOrCreate(['name' => $this->newSkill]);

        foreach ($this->newSkillCategories as $categoryId) {
            SkillCategoryLink::firstOrCreate([
                'skill_id' => $skill->id,
                'skill_category_id' => $categoryId,
            ]);
        }

        $this->newSkill = '';
        $this->newSkillCategories = [];
        $this->loadAll();

        $this->notification()->success('Success!', 'Skill added and linked to selected categories');
    }

    public function confirmDeleteSkill($id)
    {
        $this->skillToDelete = Skill::find($id);
        $this->showDeleteSkillModal = true;
    }

    public function deleteSkill()
    {
        if ($this->skillToDelete) {
            $this->skillToDelete->delete();
            $this->loadAll();
            $this->showDeleteSkillModal = false;
            $this->skillToDelete = null;

            $this->notification()->success('Deleted', 'Skill removed successfully');
        }
    }

    // ---- Categories ----
    public function confirmDeleteCategory($id)
    {
        $this->categoryToDelete = SkillCategory::find($id);
        $this->showDeleteCategoryModal = true;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            $this->categoryToDelete->delete();
            $this->loadAll();
            $this->setDefaultActiveCategory();
            $this->showDeleteCategoryModal = false;
            $this->categoryToDelete = null;

            $this->notification()->success('Deleted', 'Category removed');
        }
    }

    // ---- Bulk Linking ----
    public function linkSkillsToCategory($categoryId, $skillIds)
    {
        if (empty($skillIds)) {
            $this->notification()->error('Error', 'Please select at least one skill');
            return;
        }

        foreach ($skillIds as $skillId) {
            SkillCategoryLink::firstOrCreate([
                'skill_id' => $skillId,
                'skill_category_id' => $categoryId,
            ]);
        }

        $this->selectedSkills = [];
        $this->loadAll();

        $this->notification()->success('Success!', count($skillIds) . ' skill(s) linked to category');
    }

    public function confirmUnlinkSkill($skillId, $categoryId)
    {
        $this->skillToUnlink = Skill::find($skillId);
        $this->categoryToUnlinkFrom = SkillCategory::find($categoryId);
        $this->showUnlinkSkillModal = true;
    }

    public function unlinkSkill()
    {
        if ($this->skillToUnlink && $this->categoryToUnlinkFrom) {
            SkillCategoryLink::where([
                'skill_id' => $this->skillToUnlink->id,
                'skill_category_id' => $this->categoryToUnlinkFrom->id,
            ])->delete();

            $this->loadAll();
            $this->showUnlinkSkillModal = false;
            $this->skillToUnlink = null;
            $this->categoryToUnlinkFrom = null;

            $this->notification()->success('Unlinked', 'Skill removed from category');
        }
    }

    public function getLinkedSkills($categoryId)
    {
        return SkillCategoryLink::where('skill_category_id', $categoryId)->with('skill')->get();
    }

    public function getUnlinkedSkills($categoryId)
    {
        $linkedSkillIds = SkillCategoryLink::where('skill_category_id', $categoryId)->pluck('skill_id')->toArray();
        return Skill::whereNotIn('id', $linkedSkillIds)->orderBy('name')->get();
    }

    public function getCategorySkills($categoryId)
    {
        return SkillCategoryLink::where('skill_category_id', $categoryId)->with('skill')->get();
    }
}; ?>

<div x-data="{
    tab: @entangle('tab'),
    activeCategory: @entangle('activeCategory')
}" class="p-8 text-white" @data-updated.window="console.log('Data updated')">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

        <!-- Tabs -->
        <div class="flex space-x-6 border-b border-gray-700 mb-8">
            <button @click="tab='categories'"
                :class="tab === 'categories' ? 'border-b-2 border-white text-white' :
                    'text-white hover:text-gray-200'"
                class="pb-2 font-medium transition">
                Categories
            </button>
            <button @click="tab='skills'"
                :class="tab === 'skills' ? 'border-b-2 border-white text-white' :
                    'text-white hover:text-gray-200'"
                class="pb-2 font-medium transition">
                Skills
            </button>
        </div>

        <!-- Categories Tab -->
        <div x-show="tab==='categories'" x-cloak>
            <!-- Categories List -->
            <div class="space-y-6 text">
                @forelse ($categories as $category)
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700"
                        wire:key="category-{{ $category->id }}">
                        <div class="mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold">{{ $category->name }}</h3>
                                    @php
                                        $linkedCount = SkillCategoryLink::where(
                                            'skill_category_id',
                                            $category->id,
                                        )->count();
                                    @endphp
                                    <p class="text-sm text-gray-400 mt-1">{{ $linkedCount }} skill(s) linked</p>
                                </div>
                                {{-- <x-button wire:click="confirmDeleteCategory({{ $category->id }})" negative
                                    label="Delete Category" xs /> --}}
                            </div>
                        </div>

                        <!-- Linked Skills -->
                        <div class="mb-6">
                            <p class="text-sm text-gray-400 mb-3 uppercase tracking-wide font-medium">Linked Skills</p>
                            @php
                                $linkedSkills = $this->getLinkedSkills($category->id);
                            @endphp
                            @if ($linkedSkills->count() > 0)
                                <div class="flex flex-wrap gap-2" wire:key="linked-skills-{{ $category->id }}">
                                    @foreach ($linkedSkills as $link)
                                        <x-badge flat primary class="flex items-center gap-2"
                                            wire:key="linked-{{ $link->skill->id }}-{{ $category->id }}">
                                            {{ $link->skill->name }}
                                            <button
                                                wire:click="confirmUnlinkSkill({{ $link->skill->id }}, {{ $category->id }})"
                                                class="ml-1 hover:text-red-300 transition font-bold text-xs">
                                                ✕
                                            </button>
                                        </x-badge>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">No skills linked yet</p>
                            @endif
                        </div>

                        <!-- Add Skills (Multi-select) -->
                        @php
                            $unlinkedSkills = $this->getUnlinkedSkills($category->id);
                        @endphp
                        @if ($unlinkedSkills->count() > 0)
                            <div x-data="{ selected_{{ $category->id }}: @entangle('selectedSkills').live }">
                                <p class="text-sm text-gray-400 mb-3 uppercase tracking-wide font-medium">Add Skills</p>
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <x-select wire:model.live="selectedSkills"
                                            placeholder="Search and select skills..." :options="$unlinkedSkills"
                                            option-label="name" option-value="id" multiselect />
                                    </div>
                                    <x-button
                                        wire:click="linkSkillsToCategory({{ $category->id }}, $wire.selectedSkills)"
                                        primary label="Link Selected" icon="link" />
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">All skills are linked to this category</p>
                        @endif
                    </div>
                @empty
                    <div class="bg-gray-800 rounded-lg p-12 border border-gray-700 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            <p class="text-lg font-medium">No categories yet</p>
                            <p class="text-sm mt-2">Categories need to be added via database</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Skills Tab -->
        <div x-show="tab==='skills'" x-cloak>
            <!-- Add New Skill -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-6 max-w-2xl">
                <h2 class="text-xl font-bold mb-4">Add New Skill</h2>

                <div class="space-y-4">
                    <div>
                        <x-input wire:model.live="newSkill" label="Skill Name" placeholder="e.g., PHP, React, Docker" />
                    </div>

                    <div>
                        <x-select wire:model.live="newSkillCategories" label="Categories"
                            placeholder="Select one or more categories" :options="$categories" option-label="name"
                            option-value="id" multiselect />
                    </div>

                    <x-button wire:click="addSkill" primary label="Add Skill" icon="plus" class="w-full" />
                </div>
            </div>

            @if ($categories->count() > 0)
                <!-- Category Navigation -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-bold">Browse Skills by Category</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-wrap gap-2">
                            @foreach ($categories as $category)
                                @php
                                    $categorySkillCount = SkillCategoryLink::where(
                                        'skill_category_id',
                                        $category->id,
                                    )->count();
                                @endphp
                                <button wire:click="setActiveCategory({{ $category->id }})"
                                    :class="activeCategory == {{ $category->id }} ?
                                        'bg-indigo-600 text-white border-indigo-600' :
                                        'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600'"
                                    class="px-4 py-2 rounded-lg border transition-colors flex items-center gap-2"
                                    wire:key="nav-{{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                    <span
                                        class="text-xs bg-gray-900/50 px-2 py-1 rounded-full">{{ $categorySkillCount }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Active Category Skills -->
                @if ($activeCategory)
                    @php
                        $activeCategoryObj = $categories->find($activeCategory);
                        $categorySkills = $this->getCategorySkills($activeCategory);
                    @endphp

                    @if ($activeCategoryObj && $categorySkills->count() > 0)
                        <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden"
                            wire:key="active-category-{{ $activeCategory }}">
                            <div class="px-6 py-4 border-b border-gray-700 bg-gray-900">
                                <h2 class="text-xl font-bold">{{ $activeCategoryObj->name }}</h2>
                                <p class="text-sm text-gray-400 mt-1">{{ $categorySkills->count() }} skill(s)</p>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-900 border-b border-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                Skill</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                All Categories</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @foreach ($categorySkills as $link)
                                            <tr class="hover:bg-gray-750 transition"
                                                wire:key="skill-row-{{ $link->skill->id }}-{{ $activeCategory }}">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="font-medium">{{ $link->skill->name }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @php
                                                        $allSkillLinks = SkillCategoryLink::where(
                                                            'skill_id',
                                                            $link->skill->id,
                                                        )
                                                            ->with('category')
                                                            ->get();
                                                    @endphp
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($allSkillLinks as $skillLink)
                                                            <x-badge flat secondary
                                                                wire:key="badge-{{ $skillLink->skill_id }}-{{ $skillLink->skill_category_id }}">
                                                                {{ $skillLink->category->name }}
                                                            </x-badge>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <x-button wire:click="confirmDeleteSkill({{ $link->skill->id }})"
                                                        negative label="Delete" xs />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif($activeCategoryObj)
                        <div class="bg-gray-800 rounded-lg p-12 border border-gray-700 text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            <p class="text-gray-400 text-lg font-medium mb-2">No skills in
                                {{ $activeCategoryObj->name }}</p>
                            <p class="text-gray-500 text-sm">Add some skills to this category to see them here</p>
                        </div>
                    @endif
                @endif
            @else
                <div class="bg-gray-800 rounded-lg p-12 border border-gray-700 text-center">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <p class="text-gray-400">No categories available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Category Confirmation Modal -->
    @if ($showDeleteCategoryModal && $categoryToDelete)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Confirm Category Deletion
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        Are you sure you want to delete the category "<span
                            class="font-semibold text-red-600">{{ $categoryToDelete->name }}</span>"?
                        This action cannot be undone and will remove all skill links associated with this category.
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showDeleteCategoryModal', false)"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="deleteCategory"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-700 rounded-xl hover:from-red-700 hover:to-rose-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Delete Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Skill Confirmation Modal -->
    @if ($showDeleteSkillModal && $skillToDelete)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        Confirm Skill Deletion
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        Are you sure you want to delete the skill "<span
                            class="font-semibold text-red-600">{{ $skillToDelete->name }}</span>"?
                        This action cannot be undone and will remove this skill from all categories and portfolio
                        entries.
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showDeleteSkillModal', false)"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="deleteSkill"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-rose-700 rounded-xl hover:from-red-700 hover:to-rose-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Delete Skill
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Unlink Skill Confirmation Modal -->
    @if ($showUnlinkSkillModal && $skillToUnlink && $categoryToUnlinkFrom)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                            </path>
                        </svg>
                        Confirm Skill Unlink
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 mb-6 leading-relaxed">
                        Are you sure you want to unlink "<span
                            class="font-semibold text-orange-600">{{ $skillToUnlink->name }}</span>"
                        from the "<span
                            class="font-semibold text-orange-600">{{ $categoryToUnlinkFrom->name }}</span>" category?
                        <br><br>
                        This will only remove the link between the skill and category. The skill will remain available
                        in other categories.
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showUnlinkSkillModal', false)"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="unlinkSkill"
                            class="flex-1 px-4 py-3 text-sm font-semibold text-white bg-gradient-to-r from-orange-600 to-amber-700 rounded-xl hover:from-orange-700 hover:to-amber-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Unlink Skill
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
