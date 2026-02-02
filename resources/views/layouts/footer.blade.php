<footer class="bg-zinc-900 text-zinc-500 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-6 mb-6">
            <div>
                <h3 class="text-zinc-400 font-semibold text-sm mb-3">Krass der Wind</h3>
                <p class="text-xs leading-relaxed">
                    Politische Blasmusik aus Falkensee – für Demokratie und Menschenrechte.
                </p>
            </div>
            <div>
                <h3 class="text-zinc-400 font-semibold text-sm mb-3">Navigation</h3>
                <ul class="space-y-1.5 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-zinc-300 transition">Home</a></li>
                    <li><a href="{{ route('gigs.index') }}" class="hover:text-zinc-300 transition">Gigs</a></li>
                    <li><a href="#about" class="hover:text-zinc-300 transition">About</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-zinc-400 font-semibold text-sm mb-3">Kontakt</h3>
                <ul class="space-y-1.5 text-xs">
                    <li>info@krassderwind.de</li>
                    <li>+49 123 456789</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-zinc-800 pt-6 text-center text-xs">
            <p>&copy; {{ date('Y') }} Krass der Wind. Alle Rechte vorbehalten.</p>
        </div>
    </div>
</footer>
