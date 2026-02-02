<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="$flux.dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Krass der Wind - Brass Band</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 font-serif" x-data="{ scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 100">
    <x-navigation />

    <x-animated-background />

    <!-- Hero Section -->
    <section
        class="relative overflow-hidden bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 min-h-screen flex items-center">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Enhanced glowing orbs with HIGH intensity for hero -->
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-amber-500/35 to-orange-500/30 rounded-full blur-3xl animate-pulse">
            </div>
            <div class="absolute bottom-0 left-0 w-[700px] h-[700px] bg-gradient-to-tr from-orange-500/30 to-amber-500/28 rounded-full blur-3xl animate-pulse"
                style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[900px] h-[900px] bg-gradient-to-r from-amber-400/15 to-orange-400/15 rounded-full blur-3xl animate-spin"
                style="animation-duration: 30s;"></div>

            <!-- Additional LOUD glowing orbs -->
            <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-gradient-to-br from-amber-400/28 to-yellow-500/25 rounded-full blur-2xl animate-pulse"
                style="animation-duration: 3.5s; animation-delay: 2s;"></div>
            <div class="absolute bottom-1/3 right-1/3 w-96 h-96 bg-gradient-to-tr from-orange-400/22 to-amber-500/20 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 4.5s; animation-delay: 1.5s;"></div>
            <div class="absolute top-2/3 left-1/4 w-[400px] h-[400px] bg-gradient-to-r from-yellow-400/18 to-orange-400/18 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 5.5s;"></div>
            <div class="absolute top-10 left-1/3 w-72 h-72 bg-gradient-to-br from-amber-500/20 to-orange-400/18 rounded-full blur-2xl animate-pulse"
                style="animation-duration: 4.8s; animation-delay: 0.8s;"></div>
            <div class="absolute bottom-20 right-1/2 w-64 h-64 bg-gradient-to-tr from-yellow-500/16 to-amber-400/16 rounded-full blur-2xl animate-pulse"
                style="animation-duration: 5.2s; animation-delay: 2.5s;"></div>

            <!-- Floating musical notes - BIGGER and MORE VISIBLE -->
            <div class="absolute top-20 left-20 text-amber-500/30 animate-bounce" style="animation-duration: 3s;">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute top-40 right-40 text-orange-500/28 animate-bounce"
                style="animation-duration: 4s; animation-delay: 0.5s;">
                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute bottom-40 left-1/3 text-amber-500/22 animate-bounce"
                style="animation-duration: 5s; animation-delay: 1s;">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute top-1/3 right-1/4 text-amber-400/26 animate-bounce"
                style="animation-duration: 4.5s; animation-delay: 0.8s;">
                <svg class="w-18 h-18" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute bottom-1/4 right-1/3 text-orange-400/24 animate-bounce"
                style="animation-duration: 5.5s; animation-delay: 1.2s;">
                <svg class="w-14 h-14" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute top-2/3 left-20 text-yellow-500/20 animate-bounce"
                style="animation-duration: 4.2s; animation-delay: 1.8s;">
                <svg class="w-15 h-15" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute top-1/4 left-1/3 text-amber-400/25 animate-bounce"
                style="animation-duration: 3.8s; animation-delay: 0.3s;">
                <svg class="w-13 h-13" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute bottom-1/3 left-1/4 text-orange-500/23 animate-bounce"
                style="animation-duration: 4.7s; animation-delay: 2.2s;">
                <svg class="w-17 h-17" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>

            <!-- Decorative circles/rings - MORE VISIBLE -->
            <div class="absolute top-32 right-1/4 w-20 h-20 border-2 border-amber-400/25 rounded-full"
                style="animation: float 7s ease-in-out infinite;"></div>
            <div class="absolute bottom-1/3 left-1/4 w-24 h-24 border-3 border-orange-400/20 rounded-full"
                style="animation: float 9s ease-in-out infinite; animation-delay: 1s;"></div>
            <div class="absolute top-1/2 right-20 w-16 h-16 border-2 border-amber-300/18 rounded-full"
                style="animation: float 8s ease-in-out infinite; animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-18 h-18 border-2 border-yellow-400/22 rounded-full"
                style="animation: float 10s ease-in-out infinite; animation-delay: 0.5s;"></div>
            <div class="absolute top-1/3 left-1/4 w-22 h-22 border-2 border-amber-500/20 rounded-full"
                style="animation: float 8.5s ease-in-out infinite; animation-delay: 1.5s;"></div>
            <div class="absolute bottom-40 right-1/4 w-14 h-14 border border-orange-400/16 rounded-full"
                style="animation: float 9.5s ease-in-out infinite; animation-delay: 0.8s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-5xl lg:text-7xl font-bold mb-6 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 bg-clip-text text-transparent animate-gradient bg-[length:200%_auto] opacity-0 animate-fade-in-up"
                    style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                    Krass der Wind
                </h1>
                <p class="text-xl lg:text-2xl text-zinc-600 dark:text-zinc-400 mb-8 max-w-2xl mx-auto transform hover:scale-105 transition-transform duration-300 opacity-0 animate-fade-in-up"
                    style="animation-delay: 0.4s; animation-fill-mode: forwards;">
                    Politische Musik-Aktion! <br> Neu seit 2024 in Falkensee!
                </p>
                <div class="flex justify-center space-x-4 opacity-0 animate-fade-in-up"
                    style="animation-delay: 0.6s; animation-fill-mode: forwards;">
                    <a href="{{ route('gigs.index') }}"
                        class="group relative px-8 py-3 rounded-lg bg-amber-500 text-white font-semibold hover:bg-amber-600 transition-all duration-300 shadow-lg hover:shadow-2xl hover:shadow-amber-500/50 hover:-translate-y-1 font-sans overflow-hidden">
                        <span class="relative z-10">Unsere Auftritte</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-amber-600 to-orange-600 opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                    </a>
                    <a href="#about"
                        class="group relative px-8 py-3 rounded-lg border-2 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-zinc-100 font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all duration-300 hover:-translate-y-1 font-sans overflow-hidden">
                        <span class="relative z-10">Jetzt mitmachen!</span>
                        <div
                            class="absolute inset-0 border-2 border-amber-500 scale-0 group-hover:scale-100 transition-transform rounded-lg">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="relative py-20 overflow-hidden">
        <!-- Animated background gradient mesh -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-amber-50/50 via-transparent to-orange-50/50 dark:from-amber-950/20 dark:via-transparent dark:to-orange-950/20">
        </div>
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-500/50 to-transparent">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 scroll-reveal">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6 transform hover:translate-x-2 transition-transform duration-500">
                    <h2
                        class="text-4xl font-bold mb-6 bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                        Über Uns</h2>
                    <div class="space-y-4">
                        <p
                            class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors">
                            Wir sind Holz- und Blechbläser*innen aus Falkensee und Umgebung. Wir spielen auf
                            Veranstaltungen gegen Rechts – für Demokratie und gegen Menschenfeindlichkeit, Gewalt,
                            Ausgrenzung und das Recht des Stärkeren.
                        </p>
                        <p
                            class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors">
                            Wir musizieren für die Solidarität mit der angegriffenen Ukraine, mit klarem Bekenntnis
                            gegen Nationalismus, für europäische Werte und Menschenrechte, zu Klimaschutz und zur
                            deutschen Verantwortung für den Holocaust.
                        </p>
                        <p
                            class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors">
                            Blasinstrumente jeder Art sind willkommen – wir spielen ganz altmodisch ohne elektrische
                            Verstärkung. Mitmachen kann jede*r im Alter von 14 bis 99. Ausnahmen sind erlaubt!
                        </p>
                    </div>
                    <a href="{{ route('gigs.index') }}"
                        class="group inline-flex items-center text-amber-600 dark:text-amber-500 font-semibold hover:text-amber-700 dark:hover:text-amber-400 transition-all duration-300">
                        <span class="group-hover:mr-4 transition-all duration-300">Unsere Auftritte ansehen</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
                <div class="relative group">
                    <!-- Animated glow effect -->
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl opacity-20 group-hover:opacity-30 blur-2xl transition-opacity duration-500">
                    </div>
                    <div
                        class="relative bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-2xl aspect-[4/3] max-w-md mx-auto flex items-center justify-center shadow-2xl transform group-hover:scale-105 transition-all duration-500 overflow-hidden">
                        <!-- Animated background pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <div class="absolute inset-0 bg-gradient-to-br from-white to-transparent animate-pulse">
                            </div>
                        </div>
                        <img src="{{ asset('images/2025-kdw-funny.jpg') }}" alt="Krass der Wind Band"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-all duration-700">
                        <!-- Floating particles -->
                        <div class="absolute top-10 left-10 w-2 h-2 bg-white/40 rounded-full animate-ping"></div>
                        <div class="absolute bottom-20 right-20 w-3 h-3 bg-white/30 rounded-full animate-ping"
                            style="animation-delay: 0.5s;"></div>
                        <div class="absolute top-1/2 right-10 w-2 h-2 bg-white/50 rounded-full animate-ping"
                            style="animation-delay: 1s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smooth transition gradient between sections -->
    <div
        class="h-64 bg-gradient-to-b from-white via-amber-50/40 to-amber-100 dark:from-zinc-900 dark:via-amber-950/20 dark:to-amber-950/40">
    </div>

    <!-- CTA Section -->
    <section class="relative py-20 overflow-hidden">
        <!-- Softer gradient background with transparency -->
        <div
            class="absolute inset-0 bg-gradient-to-br from-amber-100 via-orange-50 to-amber-50 dark:from-amber-950/40 dark:via-orange-950/30 dark:to-amber-950/20">
        </div>

        <!-- Floating colorful orbs -->
        <div class="absolute inset-0 overflow-hidden">
            <!-- Large floating amber orb -->
            <div class="absolute top-10 right-1/4 w-[400px] h-[400px] bg-gradient-to-br from-amber-400/30 to-orange-400/20 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 4s;"></div>

            <!-- Medium floating orange orb -->
            <div class="absolute bottom-20 left-1/4 w-[300px] h-[300px] bg-gradient-to-tr from-orange-400/25 to-amber-300/15 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 5s; animation-delay: 1s;"></div>

            <!-- Small floating yellow orb -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-gradient-to-r from-amber-300/20 to-orange-300/15 rounded-full blur-3xl animate-pulse"
                style="animation-duration: 8s;"></div>

            <!-- Accent orbs -->
            <div class="absolute top-1/4 left-10 w-32 h-32 bg-amber-400/20 rounded-full blur-2xl animate-bounce"
                style="animation-duration: 6s;"></div>
            <div class="absolute bottom-1/4 right-10 w-40 h-40 bg-orange-400/15 rounded-full blur-2xl animate-bounce"
                style="animation-duration: 7s; animation-delay: 2s;"></div>

            <!-- Musical notes floating -->
            <div class="absolute top-20 left-1/3 text-amber-500/20 animate-bounce" style="animation-duration: 5s;">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
            <div class="absolute bottom-32 right-1/3 text-orange-500/15 animate-bounce"
                style="animation-duration: 6s; animation-delay: 1.5s;">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                </svg>
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 scroll-reveal scroll-reveal-delay-2">
            <h2
                class="text-4xl font-bold bg-gradient-to-r from-amber-600 via-orange-500 to-amber-600 bg-clip-text text-transparent mb-4 transform hover:scale-110 transition-transform duration-300">
                Bereit mitzumachen?</h2>
            <p class="text-xl text-zinc-700 dark:text-zinc-300 mb-8 max-w-2xl mx-auto leading-relaxed">
                Wir proben einmal im Monat in Falkensee. Notenkenntnisse und Spielerfahrung sind erforderlich – wir
                spielen aber als Amateurmusiker*innen auf unterschiedlichen Niveaus.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('gigs.index') }}"
                    class="group relative inline-block px-8 py-3 rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold hover:from-amber-600 hover:to-orange-600 transition-all duration-300 shadow-lg hover:shadow-2xl hover:shadow-amber-500/50 hover:-translate-y-2 font-sans overflow-hidden">
                    <span class="relative z-10">Aktuelle Auftritte</span>
                    <div
                        class="absolute inset-0 bg-white/10 scale-0 group-hover:scale-100 transition-transform rounded-lg">
                    </div>
                </a>
                <a href="https://noethernetz.de/krassderwind/" target="_blank"
                    class="group relative inline-block px-8 py-3 rounded-lg border-2 border-amber-600 dark:border-amber-500 text-amber-700 dark:text-amber-400 font-semibold hover:bg-amber-600 hover:text-white dark:hover:bg-amber-500 transition-all duration-300 hover:-translate-y-2 font-sans overflow-hidden">
                    <span class="relative z-10">Mehr erfahren</span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-amber-500/20 to-orange-500/20 scale-x-0 group-hover:scale-x-100 transition-transform origin-left">
                    </div>
                </a>
            </div>
        </div>

        <!-- Floating decorative rings -->
        <div class="absolute top-10 left-10 w-20 h-20 border border-amber-400/20 rounded-full"
            style="animation: float 10s ease-in-out infinite;"></div>
        <div class="absolute bottom-20 right-20 w-24 h-24 border border-orange-400/15 rounded-full"
            style="animation: float 12s ease-in-out infinite; animation-delay: 2s;"></div>
        <div class="absolute top-1/2 right-10 w-16 h-16 border border-amber-300/18 rounded-full"
            style="animation: float 9s ease-in-out infinite; animation-delay: 1s;"></div>
    </section>

    <!-- Footer -->
    <x-layouts::footer />

    @fluxScripts
</body>

</html>
