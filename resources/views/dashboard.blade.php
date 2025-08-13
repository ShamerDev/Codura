<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('Engineering Dashboard') }}
            </h2>
            <div class="flex items-center space-x-2 ml-auto">
                <div class="flex items-center space-x-1 bg-green-100 px-3 py-1 rounded-full">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-green-700">System Online</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="relative py-12 bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen overflow-hidden">
        <!-- Floating Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Code Blocks -->
            <div class="absolute top-20 left-10 bg-white/20 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 0s; animation-duration: 6s;">
                <div class="text-xs font-mono text-gray-600">
                    <div class="text-blue-600">function</div>
                    <div class="text-green-600">calculateMetrics()</div>
                </div>
            </div>

            <div class="absolute top-40 right-20 bg-white/30 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 2s; animation-duration: 8s;">
                <div class="text-xs font-mono text-gray-600">
                    <div class="text-purple-600">const</div>
                    <div class="text-red-600">data = await api()</div>
                </div>
            </div>

            <div class="absolute bottom-32 left-20 bg-white/25 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 4s; animation-duration: 7s;">
                <div class="text-xs font-mono text-gray-600">
                    <div class="text-indigo-600">git commit -m</div>
                    <div class="text-gray-500">"feat: update"</div>
                </div>
            </div>

            <!-- Floating Tech Icons -->
            <div class="absolute top-60 left-32 bg-white/30 backdrop-blur-sm p-2 rounded-full shadow-lg animate-pulse"
                style="animation-duration: 3s;">
                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M1.5 0h21l-1.91 21.563L11.977 24l-8.564-2.438L1.5 0zm7.031 9.75l-.232-2.718 10.059.003.23-2.622L5.412 4.41l.698 8.01h9.126l-.326 3.426-2.91.804-2.955-.81-.188-2.11H6.248l.33 4.171L12 19.351l5.379-1.443.744-8.157H8.531z" />
                </svg>
            </div>

            <div class="absolute top-80 right-40 bg-white/30 backdrop-blur-sm p-2 rounded-full shadow-lg animate-pulse"
                style="animation-delay: 1s; animation-duration: 4s;">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M0 0h24v24H0V0zm22.034 18.276c-.175-1.095-.888-2.015-3.003-2.873-.736-.345-1.554-.585-1.797-1.14-.091-.33-.105-.51-.046-.705.15-.646.915-.84 1.515-.66.39.12.75.42.976.9 1.034-.676 1.034-.676 1.755-1.125-.27-.42-.404-.601-.586-.78-.63-.705-1.469-1.065-2.834-1.034l-.705.089c-.676.165-1.32.525-1.71 1.005-1.14 1.291-.811 3.541.569 4.471 1.365 1.02 3.361 1.244 3.616 2.205.24 1.17-.87 1.545-1.966 1.41-.811-.18-1.26-.586-1.755-1.336l-1.83 1.051c.21.48.45.689.81 1.109 1.74 1.756 6.09 1.666 6.871-1.004.029-.09.24-.705.074-1.65l.046.067zm-8.983-7.245h-2.248c0 1.938-.009 3.864-.009 5.805 0 1.232.063 2.363-.138 2.711-.33.689-1.18.601-1.566.48-.396-.196-.597-.466-.83-.855-.063-.105-.11-.196-.127-.196l-1.825 1.125c.305.63.75 1.172 1.324 1.517.855.51 2.004.675 3.207.405.783-.226 1.458-.691 1.811-1.411.51-.93.402-2.07.397-3.346.012-2.054 0-4.109 0-6.179l.004-.056z" />
                </svg>
            </div>

            <!-- Geometric Shapes -->
            <div class="absolute top-32 right-60 w-16 h-16 border-2 border-blue-300/50 rounded-lg rotate-45 animate-spin"
                style="animation-duration: 20s;"></div>
            <div class="absolute bottom-40 right-32 w-12 h-12 border-2 border-purple-300/50 rounded-full animate-ping"
                style="animation-duration: 5s;"></div>
            <div
                class="absolute top-96 left-60 w-8 h-8 bg-gradient-to-r from-cyan-400/30 to-blue-500/30 rounded transform rotate-12 animate-pulse">
            </div>

            <!-- Connection Lines -->
            <svg class="absolute inset-0 w-full h-full" style="z-index: -1;">
                <defs>
                    <linearGradient id="lineGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:rgb(59, 130, 246);stop-opacity:0.2" />
                        <stop offset="100%" style="stop-color:rgb(147, 51, 234);stop-opacity:0.1" />
                    </linearGradient>
                </defs>
                <path d="M 100 200 Q 400 100 800 300" stroke="url(#lineGradient)" stroke-width="2" fill="none"
                    opacity="0.3">
                    <animate attributeName="stroke-dasharray" values="0,1000;1000,1000;0,1000" dur="8s"
                        repeatCount="indefinite" />
                </path>
                <path d="M 200 600 Q 600 400 1200 500" stroke="url(#lineGradient)" stroke-width="2" fill="none"
                    opacity="0.2">
                    <animate attributeName="stroke-dasharray" values="1000,0;0,1000;1000,0" dur="10s"
                        repeatCount="indefinite" />
                </path>
            </svg>

            <!-- Particle System -->
            <div class="absolute top-20 left-1/4 w-1 h-1 bg-blue-400 rounded-full animate-ping"
                style="animation-delay: 0s;"></div>
            <div class="absolute top-40 left-3/4 w-1 h-1 bg-purple-400 rounded-full animate-ping"
                style="animation-delay: 1s;"></div>
            <div class="absolute bottom-60 left-1/2 w-1 h-1 bg-cyan-400 rounded-full animate-ping"
                style="animation-delay: 2s;"></div>
            <div class="absolute bottom-40 left-1/3 w-1 h-1 bg-green-400 rounded-full animate-ping"
                style="animation-delay: 3s;"></div>
        </div>

        <!-- Matrix Rain Effect -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <style>
                @keyframes rain {
                    0% {
                        transform: translateY(-100vh);
                        opacity: 0;
                    }

                    10% {
                        opacity: 1;
                    }

                    90% {
                        opacity: 1;
                    }

                    100% {
                        transform: translateY(100vh);
                        opacity: 0;
                    }
                }

                .rain-column {
                    animation: rain linear infinite;
                }

                .rain-1 {
                    animation-duration: 8s;
                    animation-delay: 0s;
                }

                .rain-2 {
                    animation-duration: 12s;
                    animation-delay: 2s;
                }

                .rain-3 {
                    animation-duration: 10s;
                    animation-delay: 4s;
                }

                .rain-4 {
                    animation-duration: 15s;
                    animation-delay: 1s;
                }

                .rain-5 {
                    animation-duration: 9s;
                    animation-delay: 3s;
                }

                .rain-6 {
                    animation-duration: 11s;
                    animation-delay: 5s;
                }

                .rain-7 {
                    animation-duration: 13s;
                    animation-delay: 6s;
                }

                .rain-8 {
                    animation-duration: 7s;
                    animation-delay: 2.5s;
                }
            </style>

            <!-- Code Rain Columns -->
            <div class="rain-column rain-1 absolute left-20 text-green-400 font-mono text-xs opacity-40">
                <div class="mb-2">if (skill.level > 0) {</div>
                <div class="mb-2"> return true;</div>
                <div class="mb-2">}</div>
                <div class="mb-2">console.log('success');</div>
            </div>

            <div class="rain-column rain-2 absolute left-60 text-blue-400 font-mono text-xs opacity-35">
                <div class="mb-2">const data = await</div>
                <div class="mb-2"> fetch('/api/skills');</div>
                <div class="mb-2">return data.json();</div>
                <div class="mb-2">app.listen(3000);</div>
            </div>

            <div class="rain-column rain-3 absolute left-96 text-purple-400 font-mono text-xs opacity-30">
                <div class="mb-2">git add .</div>
                <div class="mb-2">git commit -m "feat"</div>
                <div class="mb-2">git push origin main</div>
                <div class="mb-2">git status</div>
            </div>

            <div class="rain-column rain-4 absolute right-80 text-cyan-400 font-mono text-xs opacity-35">
                <div class="mb-2">SELECT * FROM skills</div>
                <div class="mb-2">WHERE level > 5;</div>
                <div class="mb-2">ORDER BY progress;</div>
                <div class="mb-2">LIMIT 10;</div>
            </div>

            <div class="rain-column rain-5 absolute right-40 text-yellow-400 font-mono text-xs opacity-40">
                <div class="mb-2">npm install</div>
                <div class="mb-2">npm run build</div>
                <div class="mb-2">npm start</div>
                <div class="mb-2">npm test</div>
            </div>

            <div class="rain-column rain-6 absolute left-32 text-red-400 font-mono text-xs opacity-25">
                <div class="mb-2">function calculate() {</div>
                <div class="mb-2"> let sum = 0;</div>
                <div class="mb-2"> return sum;</div>
                <div class="mb-2">}</div>
            </div>

            <div class="rain-column rain-7 absolute right-20 text-indigo-400 font-mono text-xs opacity-30">
                <div class="mb-2">docker build -t app .</div>
                <div class="mb-2">docker run -p 3000</div>
                <div class="mb-2">kubectl apply -f</div>
                <div class="mb-2">docker ps</div>
            </div>

            <div class="rain-column rain-8 absolute left-80 text-pink-400 font-mono text-xs opacity-35">
                <div class="mb-2">class User extends Model</div>
                <div class="mb-2"> protected $fillable</div>
                <div class="mb-2"> public function skills()</div>
                <div class="mb-2">}</div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-2xl sm:rounded-xl border border-white/30">
                <div class="flex space-x-8 p-6">
                    <!-- Chart Component -->
                    <div
                        class="flex-1 bg-gradient-to-br from-gray-50/80 to-blue-50/80 backdrop-blur-sm rounded-lg p-4 border border-gray-100/50">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Performance Analytics</h3>
                        </div>
                        <livewire:main.chart />
                    </div>

                    <!-- Info Box Component -->
                    <div
                        class="w-1/2 bg-gradient-to-br from-purple-50/80 to-pink-50/80 backdrop-blur-sm rounded-lg p-4 border border-gray-100/50">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-purple-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">System Info</h3>
                        </div>
                        <livewire:main.info-box />
                    </div>
                </div>

                <!-- Recent Entries Section -->
                <div
                    class="bg-gradient-to-br from-green-50/80 to-yellow-50/80 backdrop-blur-sm rounded-lg p-4 border border-gray-100/50 m-6">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="bg-green-100 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h11M9 21V3m0 0L3 10m6-7l6 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Recent Entries</h3>
                    </div>
                    <livewire:main.recent />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
