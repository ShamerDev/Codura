<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public ?Profile $profile = null;
    public bool $isEditing = false;

    // Form fields
    public $bio;
    public $contact_info;
    public $linkedin;
    public $github;
    public $resumeFile; // uploaded file
    public $resume;     // stored path

    // Validation rules
    protected $rules = [
        'bio' => 'nullable|string|max:1000',
        'contact_info' => 'nullable|string|max:255',
        'linkedin' => 'nullable|url|max:255',
        'github' => 'nullable|url|max:255',
        'resumeFile' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
    ];

    protected $messages = [
        'linkedin.url' => 'LinkedIn must be a valid URL (e.g., https://linkedin.com/in/username)',
        'github.url' => 'GitHub must be a valid URL (e.g., https://github.com/username)',
        'resumeFile.mimes' => 'Resume must be a PDF file',
        'resumeFile.max' => 'Resume file size must not exceed 10MB',
    ];

    public function mount(): void
    {
        $this->profile = Profile::where('user_id', auth()->id())->first();

        if ($this->profile) {
            $this->bio = $this->profile->bio;
            $this->contact_info = $this->profile->contact_info;
            $this->linkedin = $this->profile->linkedin;
            $this->github = $this->profile->github;
            $this->resume = $this->profile->resume;
        }
    }

    public function edit(): void
    {
        $this->isEditing = true;
    }

    public function cancel(): void
    {
        $this->isEditing = false;
        $this->resumeFile = null;
        $this->resetValidation();

        // Reset form fields to original values
        if ($this->profile) {
            $this->bio = $this->profile->bio;
            $this->contact_info = $this->profile->contact_info;
            $this->linkedin = $this->profile->linkedin;
            $this->github = $this->profile->github;
            $this->resume = $this->profile->resume;
        }
    }

    public function save(): void
    {
        $this->validate();

        // Handle resume upload if new file
        if ($this->resumeFile) {
            // Delete old resume if exists
            if ($this->resume && Storage::disk('public')->exists($this->resume)) {
                Storage::disk('public')->delete($this->resume);
            }

            $this->resume = $this->resumeFile->store('resumes', 'public');
        }

        $this->profile = Profile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'bio' => $this->bio,
                'contact_info' => $this->contact_info,
                'linkedin' => $this->linkedin,
                'github' => $this->github,
                'resume' => $this->resume,
            ]
        );

        $this->isEditing = false;
        $this->resumeFile = null;

        $this->dispatch('notify', [
            'message' => 'Profile updated successfully!',
            'type' => 'success',
        ]);
    }

    public function removeResume(): void
    {
        if ($this->resume && Storage::disk('public')->exists($this->resume)) {
            Storage::disk('public')->delete($this->resume);
        }

        $this->resume = null;

        if ($this->profile) {
            $this->profile->update(['resume' => null]);
        }

        $this->dispatch('notify', [
            'message' => 'Resume removed successfully!',
            'type' => 'success',
        ]);
    }
};
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50 p-6">
    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-20 w-64 h-64 bg-gradient-to-br from-blue-200/20 to-purple-200/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-gradient-to-br from-purple-200/20 to-pink-200/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-sm rounded-full border border-white/40 text-sm font-medium text-gray-600 mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile Management
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4">
                My Profile
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Manage your professional information and showcase your expertise
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto mt-6 rounded-full"></div>
        </div>

        @if(!$isEditing)
            <!-- Overview Mode -->
            <div class="space-y-8">
                <!-- Bio Section -->
                <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Professional Bio</h2>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50/80 to-blue-50/40 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
                        <p class="text-gray-700 leading-relaxed text-lg">
                            {{ $profile?->bio ?? 'No bio added yet. Add a professional summary to help others understand your background and expertise.' }}
                        </p>
                    </div>
                </div>

                <!-- Contact Info Section -->
                <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Contact Information</h2>
                    </div>
                    <div class="bg-gradient-to-br from-gray-50/80 to-emerald-50/40 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
                        <p class="text-gray-700 leading-relaxed text-lg">
                            {{ $profile?->contact_info ?? 'No contact information provided. Add your email or phone number for professional inquiries.' }}
                        </p>
                    </div>
                </div>

                <!-- Social Links Grid -->
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- LinkedIn -->
                    <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40 hover:shadow-2xl transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">LinkedIn</h2>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50/80 to-blue-50/40 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
                            @if($profile?->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank" class="group inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-300">
                                    <span class="font-medium">View LinkedIn Profile</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7h-4M17 7v4"></path>
                                    </svg>
                                </a>
                                <p class="text-sm text-gray-500 mt-2 break-all">{{ $profile->linkedin }}</p>
                            @else
                                <p class="text-gray-600 font-medium">Not connected yet. Link your LinkedIn profile to showcase your professional network.</p>
                            @endif
                        </div>
                    </div>

                    <!-- GitHub -->
                    <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40 hover:shadow-2xl transition-all duration-300 group">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">GitHub</h2>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50/80 to-gray-100/40 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
                            @if($profile?->github)
                                <a href="{{ $profile->github }}" target="_blank" class="group inline-flex items-center text-gray-800 hover:text-gray-900 transition-colors duration-300">
                                    <span class="font-medium">View GitHub Profile</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7h-4M17 7v4"></path>
                                    </svg>
                                </a>
                                <p class="text-sm text-gray-500 mt-2 break-all">{{ $profile->github }}</p>
                            @else
                                <p class="text-gray-600 font-medium">Not connected yet. Link your GitHub profile to showcase your code repositories.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Resume Section -->
                <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-800">Resume</h2>
                        </div>
                        @if($profile?->resume)
                            <button wire:click="removeResume" onclick="return confirm('Are you sure you want to remove your resume?')" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                    <div class="bg-gradient-to-br from-gray-50/80 to-purple-50/40 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
                        @if($profile?->resume)
                            <a href="{{ Storage::url($profile->resume) }}" target="_blank" class="group inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-xl font-medium hover:shadow-lg transition-all duration-300 hover:scale-105">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Resume
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M7 7l10 10M17 7h-4M17 7v4"></path>
                                </svg>
                            </a>
                            <p class="text-sm text-gray-500 mt-4">PDF document ready for download</p>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 font-medium">No resume uploaded yet.</p>
                                <p class="text-gray-500 text-sm mt-2">Upload your resume to make it easily accessible to potential employers.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="text-center pt-8">
                    <button wire:click="edit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl font-bold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 group">
                        <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </button>
                </div>
            </div>
        @else
            <!-- Edit Mode -->
            <div class="bg-white/70 backdrop-blur-sm shadow-xl rounded-3xl p-8 border border-white/40">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Edit Profile</h2>
                    <p class="text-gray-600">Update your professional information</p>
                </div>

                <form wire:submit.prevent="save" class="space-y-8">
                    <!-- Bio -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Professional Bio</label>
                        <textarea
                            wire:model.defer="bio"
                            class="w-full border-2 border-gray-200 rounded-2xl p-4 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 resize-none"
                            rows="4"
                            placeholder="Tell us about your professional background, skills, and interests..."
                            maxlength="1000"
                        ></textarea>
                        @error('bio') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        <p class="text-sm text-gray-500 mt-2">{{ strlen($bio ?? '') }}/1000 characters</p>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Contact Information</label>
                        <input
                            type="text"
                            wire:model.defer="contact_info"
                            class="w-full border-2 border-gray-200 rounded-2xl p-4 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"
                            placeholder="Email, phone number, or other contact details..."
                        />
                        @error('contact_info') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>

                    <!-- Social Links Grid -->
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- LinkedIn -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-800 mb-3">LinkedIn Profile</label>
                            <input
                                type="url"
                                wire:model.defer="linkedin"
                                class="w-full border-2 border-gray-200 rounded-2xl p-4 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"
                                placeholder="https://linkedin.com/in/username"
                            />
                            @error('linkedin') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>

                        <!-- GitHub -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-800 mb-3">GitHub Profile</label>
                            <input
                                type="url"
                                wire:model.defer="github"
                                class="w-full border-2 border-gray-200 rounded-2xl p-4 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300"
                                placeholder="https://github.com/username"
                            />
                            @error('github') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Resume Upload -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-800 mb-3">Resume (PDF)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-blue-400 transition-colors duration-300">
                            <input
                                type="file"
                                wire:model="resumeFile"
                                class="hidden"
                                accept="application/pdf"
                                id="resume-upload"
                            />
                            <label for="resume-upload" class="cursor-pointer">
                                <div class="mb-4">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-lg font-medium text-gray-700 mb-2">Click to upload your resume</p>
                                <p class="text-sm text-gray-500">PDF files only, max 10MB</p>
                            </label>
                        </div>

                        @error('resumeFile') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror

                        @if($resumeFile)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <p class="text-green-800 font-medium">New file selected: {{ $resumeFile->getClientOriginalName() }}</p>
                            </div>
                        @elseif($resume)
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-blue-800 font-medium">
                                    Current resume:
                                    <a href="{{ Storage::url($resume) }}" target="_blank" class="underline hover:no-underline">
                                        View current resume
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-8 border-t border-gray-200">
                        <button
                            type="button"
                            wire:click="cancel"
                            class="px-8 py-3 bg-gray-200 text-gray-800 rounded-2xl font-semibold hover:bg-gray-300 transition-all duration-300 group"
                        >
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </span>
                        </button>

                        <button
                            type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 group"
                        >
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Changes
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
