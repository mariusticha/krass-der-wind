<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Krass der Wind - Brass Band</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100">
        <x-public-nav />

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-bold mb-6 bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent">
                        Krass der Wind
                    </h1>
                    <p class="text-xl lg:text-2xl text-zinc-600 dark:text-zinc-400 mb-8 max-w-2xl mx-auto">
                        Politische Musik-Aktion! Neu seit 2024 in Falkensee!
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('gigs.index') }}" class="px-8 py-3 rounded-lg bg-amber-500 text-white font-semibold hover:bg-amber-600 transition shadow-lg">
                            Unsere Auftritte
                        </a>
                        <a href="#about" class="px-8 py-3 rounded-lg border-2 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-zinc-100 font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                            Jetzt mitmachen!
                        </a>
                    </div>
                </div>
            </div>

            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
        </section>

        <!-- About Section -->
        <section id="about" class="py-20 bg-white dark:bg-zinc-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold mb-6">Über Uns</h2>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-4">
                            Wir sind Holz- und Blechbläser*innen aus Falkensee und Umgebung. Wir spielen auf Veranstaltungen gegen Rechts – für Demokratie und gegen Menschenfeindlichkeit, Gewalt, Ausgrenzung und das Recht des Stärkeren.
                        </p>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-4">
                            Wir musizieren für die Solidarität mit der angegriffenen Ukraine, mit klarem Bekenntnis gegen Nationalismus, für europäische Werte und Menschenrechte, zu Klimaschutz und zur deutschen Verantwortung für den Holocaust.
                        </p>
                        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6">
                            Blasinstrumente jeder Art sind willkommen – wir spielen ganz altmodisch ohne elektrische Verstärkung. Mitmachen kann jede*r im Alter von 14 bis 99. Ausnahmen sind erlaubt!
                        </p>
                        <a href="{{ route('gigs.index') }}" class="inline-flex items-center text-amber-600 dark:text-amber-500 font-semibold hover:text-amber-700 dark:hover:text-amber-400">
                            Unsere Auftritte ansehen
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl aspect-square flex items-center justify-center shadow-2xl">
                        <svg class="w-48 h-48 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-br from-amber-500 to-orange-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold text-white mb-4">Bereit mitzumachen?</h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Wir proben einmal im Monat in Falkensee. Notenkenntnisse und Spielerfahrung sind erforderlich – wir spielen aber als Amateurmusiker*innen auf unterschiedlichen Niveaus.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('gigs.index') }}" class="inline-block px-8 py-3 rounded-lg bg-white text-amber-600 font-semibold hover:bg-zinc-100 transition shadow-lg">
                        Aktuelle Auftritte
                    </a>
                    <a href="https://noethernetz.de/krassderwind/" target="_blank" class="inline-block px-8 py-3 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                        Mehr erfahren
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-zinc-900 text-zinc-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4">Krass der Wind</h3>
                        <p class="text-sm">
                            Politische Blasmusik aus Falkensee – für Demokratie und Menschenrechte.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4">Navigation</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                            <li><a href="{{ route('gigs.index') }}" class="hover:text-white transition">Gigs</a></li>
                            <li><a href="#about" class="hover:text-white transition">About</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-4">Kontakt</h3>
                        <ul class="space-y-2 text-sm">
                            <li>info@krassderwind.de</li>
                            <li>+49 123 456789</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-zinc-800 pt-8 text-center text-sm">
                    <p>&copy; {{ date('Y') }} Krass der Wind. Alle Rechte vorbehalten.</p>
                </div>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
            </div>
        </footer>

        @fluxScripts
    </body>
</html>
