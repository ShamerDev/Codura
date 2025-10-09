<x-app-layout>
    <div
        class="relative py-12 min-h-screen overflow-hidden bg-gradient-to-br from-slate-700 via-purple-600 to-slate-700">
        <!-- Enhanced Background Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">

            <!-- VS Code Style Editor - Kept but repositioned -->
            <div class="absolute top-20 right-16 bg-gray-900/90 backdrop-blur-sm rounded-lg shadow-2xl border border-gray-700 w-96 h-64 animate-float"
                style="animation-delay: 1s;">
                <div class="bg-gray-800 px-4 py-2 rounded-t-lg flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    </div>
                    <span class="text-gray-300 text-sm font-mono">Portfolio.js</span>
                </div>
                <div class="p-4 font-mono text-xs">
                    <div class="text-purple-400">import <span class="text-blue-400">React</span> from <span
                            class="text-green-400">'react'</span>;</div>
                    <div class="mt-1 text-purple-400">import <span class="text-blue-400">{ useState }</span> from <span
                            class="text-green-400">'react'</span>;</div>
                    <div class="mt-2 text-blue-400">const <span class="text-yellow-400">Portfolio</span> = () => {</div>
                    <div class="ml-4 text-purple-400">const [<span class="text-blue-400">projects</span>, <span
                            class="text-blue-400">setProjects</span>] = <span
                            class="text-yellow-400">useState</span>([]);</div>
                    <div class="ml-4 mt-2 text-orange-400">return (</div>
                    <div class="ml-8 text-gray-300">&lt;<span class="text-red-400">div</span> <span
                            class="text-blue-400">className</span>=<span class="text-green-400">"portfolio"</span>&gt;
                    </div>
                    <div class="ml-12 code-cursor">|</div>
                </div>
            </div>

            <!-- New: Glowing Orbs -->
            <div
                class="absolute top-1/4 left-1/3 w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 blur-2xl opacity-30 animate-pulse-slow">
            </div>
            <div class="absolute bottom-1/3 right-1/4 w-32 h-32 rounded-full bg-gradient-to-br from-green-400 to-cyan-500 blur-2xl opacity-30 animate-pulse-slow"
                style="animation-delay: 1.5s;"></div>
            <div class="absolute top-2/3 left-1/5 w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-pink-500 blur-2xl opacity-30 animate-pulse-slow"
                style="animation-delay: 0.8s;"></div>

            <!-- New: Floating 3D Shapes -->
            <div class="absolute top-40 left-20 animate-float-slow">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg shadow-xl transform rotate-45 opacity-80">
                </div>
            </div>
            <div class="absolute bottom-60 right-40 animate-float-slow" style="animation-delay: 2s;">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full shadow-xl opacity-80">
                </div>
            </div>
            <div class="absolute top-1/2 right-1/4 animate-float-slow" style="animation-delay: 1s;">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-500 rounded-lg shadow-xl transform rotate-12 opacity-80">
                </div>
            </div>

            <!-- New: Floating Badges -->
            <div
                class="absolute top-20 left-20 bg-gradient-to-r from-cyan-500 to-blue-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob">
                React
            </div>
            <div class="absolute top-48 left-28 bg-gradient-to-r from-violet-500 to-purple-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 0.7s;">
                TypeScript
            </div>
            <div class="absolute bottom-48 left-32 bg-gradient-to-r from-emerald-500 to-green-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 1.3s;">
                Node.js
            </div>
            <div class="absolute top-32 right-32 bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2 rounded-full shadow-lg text-white text-xs font-medium animate-bob"
                style="animation-delay: 0.4s;">
                Vue.js
            </div>

            <!-- API Response Simulation - Kept -->
            <div
                class="absolute bottom-64 left-20 bg-gray-900/85 backdrop-blur-sm rounded-lg p-4 shadow-xl border border-gray-700 w-64 animate-slide-up">
                <div class="font-mono text-xs">
                    <div class="text-green-400 mb-2">200 OK</div>
                    <div class="text-gray-300">{</div>
                    <div class="ml-2 text-blue-400">"status": <span class="text-green-400">"success"</span>,</div>
                    <div class="ml-2 text-blue-400">"data": {</div>
                    <div class="ml-4 text-purple-400">"user": <span class="text-yellow-400">"developer"</span>,</div>
                    <div class="ml-4 text-purple-400">"skills": <span class="text-red-400">[...]</span></div>
                    <div class="ml-2">}</div>
                    <div class="text-gray-300">}</div>
                </div>
            </div>

            <!-- Particle System - Enhanced with more particles -->
            <div class="absolute inset-0">
                <div class="particle bg-cyan-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 15%; left: 15%; animation-duration: 8s;"></div>
                <div class="particle bg-blue-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 25%; left: 75%; animation-duration: 6s; animation-delay: 1s;"></div>
                <div class="particle bg-purple-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 50%; left: 50%; animation-duration: 7s; animation-delay: 2s;"></div>
                <div class="particle bg-green-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 75%; left: 25%; animation-duration: 9s; animation-delay: 0.5s;"></div>
                <div class="particle bg-yellow-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 85%; left: 85%; animation-duration: 8s; animation-delay: 1.5s;"></div>
                <div class="particle bg-red-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 35%; left: 40%; animation-duration: 7.5s; animation-delay: 1.2s;"></div>
                <div class="particle bg-pink-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 20%; left: 60%; animation-duration: 9s; animation-delay: 2.2s;"></div>
                <div class="particle bg-indigo-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 65%; left: 70%; animation-duration: 7s; animation-delay: 0.8s;"></div>
                <div class="particle bg-teal-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 45%; left: 15%; animation-duration: 8.5s; animation-delay: 1.8s;"></div>
                <div class="particle bg-amber-400 w-2 h-2 rounded-full absolute animate-float-particle"
                    style="top: 10%; left: 40%; animation-duration: 7.2s; animation-delay: 1.3s;"></div>
            </div>

            <!-- New: Sparkly Stars -->
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 15%; left: 10%;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 25%; left: 35%; animation-delay: 0.5s;"></div>
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 10%; left: 85%; animation-delay: 1s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 45%; left: 88%; animation-delay: 1.5s;"></div>
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 85%; left: 22%; animation-delay: 0.3s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 77%; left: 80%; animation-delay: 0.8s;"></div>
            <div class="sparkle absolute w-2 h-2 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 60%; left: 5%; animation-delay: 1.3s;"></div>
            <div class="sparkle absolute w-1 h-1 bg-white rounded-full blur-sm animate-twinkle"
                style="top: 35%; left: 65%; animation-delay: 1.8s;"></div>

            <!-- Animated connection lines between elements -->
            <svg class="absolute inset-0 w-full h-full opacity-30" style="z-index: -1;">
                <defs>
                    <linearGradient id="codeGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#8B5CF6;stop-opacity:0.8" />
                        <stop offset="50%" style="stop-color:#3B82F6;stop-opacity:0.6" />
                        <stop offset="100%" style="stop-color:#10B981;stop-opacity:0.4" />
                    </linearGradient>
                </defs>

                <!-- Dynamic connection paths - Enhanced with more paths -->
                <path class="connection-line" d="M 150 200 Q 400 150 650 250" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none">
                    <animate attributeName="stroke-dasharray" values="0,1000;1000,0;0,1000" dur="6s"
                        repeatCount="indefinite" />
                </path>

                <path class="connection-line" d="M 800 300 Q 500 400 200 350" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none" opacity="0.6">
                    <animate attributeName="stroke-dasharray" values="0,800;800,0;0,800" dur="8s"
                        repeatCount="indefinite" begin="2s" />
                </path>

                <path class="connection-line" d="M 300 100 Q 500 200 700 100" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none" opacity="0.7">
                    <animate attributeName="stroke-dasharray" values="0,900;900,0;0,900" dur="7s"
                        repeatCount="indefinite" begin="1s" />
                </path>

                <path class="connection-line" d="M 200 500 Q 400 300 600 500" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none" opacity="0.5">
                    <animate attributeName="stroke-dasharray" values="0,850;850,0;0,850" dur="9s"
                        repeatCount="indefinite" begin="3s" />
                </path>

                <path class="connection-line" d="M 150 400 Q 300 250 450 350" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none" opacity="0.6">
                    <animate attributeName="stroke-dasharray" values="0,600;600,0;0,600" dur="7.5s"
                        repeatCount="indefinite" begin="0.5s" />
                </path>

                <path class="connection-line" d="M 500 150 Q 650 250 800 150" stroke="url(#codeGradient)"
                    stroke-width="2" fill="none" opacity="0.7">
                    <animate attributeName="stroke-dasharray" values="0,700;700,0;0,700" dur="8.5s"
                        repeatCount="indefinite" begin="1.5s" />
                </path>
            </svg>
        </div>

        <!-- Enhanced CSS Animations -->
        <style>
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

            @keyframes ping-slow {
                0% {
                    transform: scale(1);
                    opacity: 1;
                }

                75%,
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .animate-ping-slow {
                animation: ping-slow 2s cubic-bezier(0, 0, 0.2, 1) infinite;
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

            @keyframes animate-slide-up {
                0% {
                    transform: translateY(20px);
                    opacity: 0;
                }

                100% {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .animate-slide-up {
                animation: animate-slide-up 1s ease-out;
            }

            .db-query-animation {
                animation: query-execute 3s infinite;
            }

            @keyframes query-execute {

                0%,
                70% {
                    color: #60A5FA;
                }

                80%,
                90% {
                    color: #10B981;
                }

                100% {
                    color: #60A5FA;
                }
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

        <!-- Main Content -->
        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <div class="space-y-8">
                    <livewire:user.viewentry>
                </div>
            </div>
        </div>
    </div>

    <x-dialog z-index="z-50" blur="md" align="center" />
    <x-notifications position="top-end" />
</x-app-layout>
