<x-app-layout>
    <div
        class="relative py-12 bg-gradient-to-br from-slate-700 via-purple-600 to-slate-700 min-h-screen overflow-hidden">
        <!-- Better distributed background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Code Blocks - Better positioned -->
            <div class="absolute top-24 left-16 bg-gray-900/80 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 0s; animation-duration: 6s;">
                <div class="text-xs font-mono text-gray-300">
                    <div class="text-blue-400">function</div>
                    <div class="text-green-400">calculateMetrics()</div>
                </div>
            </div>

            <div class="absolute top-64 right-28 bg-gray-900/80 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 2s; animation-duration: 8s;">
                <div class="text-xs font-mono text-gray-300">
                    <div class="text-purple-400">const</div>
                    <div class="text-red-400">data = await api()</div>
                </div>
            </div>

            <div class="absolute bottom-48 left-32 bg-gray-900/80 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 4s; animation-duration: 7s;">
                <div class="text-xs font-mono text-gray-300">
                    <div class="text-indigo-400">git commit -m</div>
                    <div class="text-gray-400">"feat: update"</div>
                </div>
            </div>

            <!-- Added new code block for better distribution -->
            <div class="absolute bottom-36 right-40 bg-gray-900/80 backdrop-blur-sm rounded-lg p-3 shadow-lg animate-bounce"
                style="animation-delay: 3s; animation-duration: 7.5s;">
                <div class="text-xs font-mono text-gray-300">
                    <div class="text-yellow-400">import React</div>
                    <div class="text-blue-400">from 'react'</div>
                </div>
            </div>

            <!-- Glowing Orbs - Better positioned -->
            <div
                class="absolute top-1/3 left-1/4 w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 blur-2xl opacity-30 animate-pulse-slow">
            </div>
            <div class="absolute top-3/4 right-1/3 w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-cyan-500 blur-2xl opacity-30 animate-pulse-slow"
                style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/5 right-1/4 w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-pink-500 blur-2xl opacity-30 animate-pulse-slow"
                style="animation-delay: 0.8s;"></div>
            <!-- Added new orb for better distribution -->
            <div class="absolute bottom-1/6 left-1/3 w-28 h-28 rounded-full bg-gradient-to-br from-orange-400 to-red-500 blur-2xl opacity-30 animate-pulse-slow"
                style="animation-delay: 2.2s;"></div>

            <!-- Floating Tech Icons - Better positioned -->
            <div class="absolute top-48 left-1/4 bg-gray-900/80 backdrop-blur-sm p-2 rounded-full shadow-lg tech-icon text-blue-500 animate-pulse"
                style="animation-duration: 3s;">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M1.5 0h21l-1.91 21.563L11.977 24l-8.564-2.438L1.5 0zm7.031 9.75l-.232-2.718 10.059.003.23-2.622L5.412 4.41l.698 8.01h9.126l-.326 3.426-2.91.804-2.955-.81-.188-2.11H6.248l.33 4.171L12 19.351l5.379-1.443.744-8.157H8.531z" />
                </svg>
            </div>
            <!-- Added new tech icon for better distribution -->
            <div class="absolute bottom-1/3 left-1/6 bg-gray-900/80 backdrop-blur-sm p-2 rounded-full shadow-lg tech-icon text-purple-500 animate-pulse"
                style="animation-delay: 2s; animation-duration: 3.5s;">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                </svg>
            </div>

            <!-- Floating 3D Shapes - Better positioned -->
            <div class="absolute top-1/5 left-1/6 animate-float-slow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-xl transform rotate-45 opacity-80">
                </div>
            </div>

            <div class="absolute bottom-1/4 right-1/6 animate-float-slow" style="animation-delay: 2s;">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full shadow-xl opacity-80">
                </div>
            </div>

            <!-- Added new 3D shape for better distribution -->
            <div class="absolute top-2/3 right-1/4 animate-float-slow" style="animation-delay: 3s;">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg shadow-xl transform rotate-12 opacity-80">
                </div>
            </div>

            <!-- Floating Badges - Better positioned -->
            <div
                class="absolute top-36 left-1/3 bg-gradient-to-r from-cyan-500 to-blue-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob">
                React
            </div>

            <div class="absolute top-2/3 right-60 bg-gradient-to-r from-violet-500 to-purple-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 0.7s;">
                TypeScript
            </div>

            <!-- Added new floating badges for better distribution -->
            <div class="absolute bottom-1/3 left-52 bg-gradient-to-r from-green-500 to-emerald-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 1.3s;">
                Node.js
            </div>

            <div class="absolute top-1/4 right-48 bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 2.1s;">
                Vue.js
            </div>

            <!-- Connection Lines - Better positioned -->
            <svg class="absolute inset-0 w-full h-full" style="z-index: -1;">
                <defs>
                    <linearGradient id="lineGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:rgb(139, 92, 246);stop-opacity:0.6" />
                        <stop offset="100%" style="stop-color:rgb(59, 130, 246);stop-opacity:0.3" />
                    </linearGradient>
                </defs>
                <path d="M 150 250 Q 450 150 750 350" stroke="url(#lineGradient)" stroke-width="2" fill="none">
                    <animate attributeName="stroke-dasharray" values="0,1000;1000,1000;0,1000" dur="8s"
                        repeatCount="indefinite" />
                </path>
                <path d="M 250 550 Q 550 350 950 450" stroke="url(#lineGradient)" stroke-width="2" fill="none">
                    <animate attributeName="stroke-dasharray" values="1000,0;0,1000;1000,0" dur="10s"
                        repeatCount="indefinite" />
                </path>
                <!-- Added new connection line for better distribution -->
                <path d="M 350 150 Q 550 450 850 200" stroke="url(#lineGradient)" stroke-width="2" fill="none">
                    <animate attributeName="stroke-dasharray" values="0,1000;1000,0;0,1000" dur="9s"
                        repeatCount="indefinite" />
                </path>
            </svg>

            <!-- Sparkly Stars - Better positioned -->
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 12%; left: 18%;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 28%; left: 42%; animation-delay: 0.5s;"></div>
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 15%; left: 75%; animation-delay: 1s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 55%; left: 82%; animation-delay: 1.5s;"></div>
            <!-- Added more sparkly stars for better distribution -->
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 68%; left: 22%; animation-delay: 0.7s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 42%; left: 55%; animation-delay: 1.2s;"></div>
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 80%; left: 65%; animation-delay: 1.8s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 36%; left: 12%; animation-delay: 2.3s;"></div>

            <!-- Particle System - Better positioned -->
            <div class="particle bg-cyan-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 20%; left: 25%; animation-duration: 8s;"></div>
            <div class="particle bg-blue-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 35%; left: 65%; animation-duration: 6s; animation-delay: 1s;"></div>
            <div class="particle bg-purple-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 60%; left: 40%; animation-duration: 7s; animation-delay: 2s;"></div>
            <div class="particle bg-green-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 80%; left: 15%; animation-duration: 9s; animation-delay: 0.5s;"></div>
            <!-- Added more particles for better distribution -->
            <div class="particle bg-yellow-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 15%; left: 55%; animation-duration: 7.5s; animation-delay: 1.8s;"></div>
            <div class="particle bg-red-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 50%; left: 85%; animation-duration: 8.5s; animation-delay: 0.8s;"></div>
            <div class="particle bg-indigo-400 w-2 h-2 rounded-full absolute animate-float-particle"
                style="top: 75%; left: 60%; animation-duration: 6.5s; animation-delay: 2.3s;"></div>
        </div>

        <!-- Matrix Rain Effect Styles - Keep existing styles -->
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

            @keyframes typing-animation {

                0%,
                20% {
                    width: 0;
                }

                50%,
                80% {
                    width: 100%;
                }

                100% {
                    width: 0;
                }
            }

            .typing-animation {
                overflow: hidden;
                border-right: 2px solid #10B981;
                white-space: nowrap;
                animation: typing-animation 4s infinite;
            }

            .code-cursor {
                animation: blink 1s infinite;
                color: #10B981;
            }

            @keyframes blink {

                0%,
                50% {
                    opacity: 1;
                }

                51%,
                100% {
                    opacity: 0;
                }
            }

            @keyframes animate-float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg);
                }

                50% {
                    transform: translateY(-10px) rotate(2deg);
                }
            }

            .animate-float {
                animation: animate-float 6s ease-in-out infinite;
            }

            /* New animations */
            .animate-float-slow {
                animation: animate-float 8s ease-in-out infinite;
            }

            @keyframes animate-bob {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-8px);
                }
            }

            .animate-bob {
                animation: animate-bob 4s ease-in-out infinite;
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 0.7;
                }

                50% {
                    opacity: 1;
                }
            }

            .animate-pulse-slow {
                animation: pulse-slow 4s ease-in-out infinite;
            }

            @keyframes twinkle {

                0%,
                100% {
                    opacity: 0.2;
                }

                50% {
                    opacity: 1;
                }
            }

            .animate-twinkle {
                animation: twinkle 3s ease-in-out infinite;
            }

            @keyframes animate-float-particle {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 0;
                }

                10% {
                    opacity: 1;
                }

                90% {
                    opacity: 1;
                }

                100% {
                    transform: translate(100px, -100px) rotate(180deg);
                    opacity: 0;
                }
            }

            .animate-float-particle {
                animation: animate-float-particle linear infinite;
            }

            .tech-icon {
                box-shadow: 0 0 20px currentColor;
                transition: all 0.3s ease;
            }

            .tech-icon:hover {
                transform: scale(1.1);
                box-shadow: 0 0 30px currentColor;
            }
        </style>

        <!-- Matrix Rain Code Columns - Better positioned -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Code Rain Columns - Adjusted positioning -->
            <div class="rain-column rain-1 absolute left-[10%] text-green-400 font-mono text-xs opacity-40">
                <div class="mb-2">if (skill.level > 0) {</div>
                <div class="mb-2"> return true;</div>
                <div class="mb-2">}</div>
                <div class="mb-2">console.log('success');</div>
            </div>

            <div class="rain-column rain-2 absolute left-[30%] text-blue-400 font-mono text-xs opacity-35">
                <div class="mb-2">const data = await</div>
                <div class="mb-2"> fetch('/api/skills');</div>
                <div class="mb-2">return data.json();</div>
                <div class="mb-2">app.listen(3000);</div>
            </div>

            <div class="rain-column rain-3 absolute left-[50%] text-purple-400 font-mono text-xs opacity-30">
                <div class="mb-2">git add .</div>
                <div class="mb-2">git commit -m "feat"</div>
                <div class="mb-2">git push origin main</div>
                <div class="mb-2">git status</div>
            </div>

            <div class="rain-column rain-4 absolute left-[70%] text-cyan-400 font-mono text-xs opacity-35">
                <div class="mb-2">SELECT * FROM skills</div>
                <div class="mb-2">WHERE level > 5;</div>
                <div class="mb-2">ORDER BY progress;</div>
                <div class="mb-2">LIMIT 10;</div>
            </div>

            <div class="rain-column rain-5 absolute left-[90%] text-yellow-400 font-mono text-xs opacity-40">
                <div class="mb-2">npm install</div>
                <div class="mb-2">npm run build</div>
                <div class="mb-2">npm start</div>
                <div class="mb-2">npm test</div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white backdrop-blur-sm overflow-hidden shadow-2xl sm:rounded-xl">
                <div class="flex space-x-8 p-6">
                    <!-- Chart Component - Much Brighter -->
                    <div
                        class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-400 backdrop-blur-sm rounded-lg p-4 border border-blue-300/50 shadow-lg shadow-blue-500/20">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-white p-2 rounded-lg shadow-md">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white tracking-wide">Performance Analytics</h3>
                        </div>
                        <livewire:main.chart />
                    </div>

                    <!-- Info Box Component - Much Brighter -->
                    <div
                        class="w-1/2 bg-gradient-to-r from-indigo-600 to-purple-600 backdrop-blur-sm rounded-lg p-4 border border-fuchsia-300/50 shadow-lg shadow-fuchsia-500/20">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="bg-white p-2 rounded-lg shadow-md">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white tracking-wide">System Info</h3>
                        </div>
                        <livewire:main.info-box />
                    </div>
                </div>

                <!-- Recent Entries Section - Much Brighter -->
                <div
                    class="bg-gradient-to-r from-indigo-600 to-purple-300 backdrop-blur-sm rounded-lg p-4 m-6 border border-emerald-300/50 shadow-lg shadow-emerald-500/20">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="bg-white p-2 rounded-lg shadow-md">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white tracking-wide">Your Entries</h3>
                    </div>
                    <livewire:main.recent />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
