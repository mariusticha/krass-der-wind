<x-layouts::app title="Impressum – KrassderWind">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 relative z-10">
        <article class="text-zinc-600 dark:text-zinc-400 space-y-6">
            <x-ui.heading-1 title="Impressum" />

            <x-ui.heading-2 title="Angaben gemäß § 5 TMG" variant="decent" />

            <p>
                <strong>{{ $first_name }} {{ $last_name }}</strong><br>
                {{ $street }}<br>
                {{ $postal_code }} {{ $city }}<br>
                {{ $country }}
            </p>

            <x-ui.heading-2 title="Kontakt" variant="decent" />

            <p>
                E-Mail: <a href="mailto:{{ $email }}">{{ $email }}</a><br>
            </p>

            <x-ui.heading-2 title="Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV" variant="decent" />

            <p>
                <strong>{{ $first_name }} {{ $last_name }}</strong><br>
                {{ $street }}<br>
                {{ $postal_code }} {{ $city }}<br>
                {{ $country }}<br>
            </p>

            <hr class="border-zinc-200
                    dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="Haftungsausschluss" variant="decent" />

            <h3 class="text-lg font-medium
                        text-zinc-800 dark:text-zinc-200 mt-6 mb-2">Haftung
                für Inhalte</h3>

            <p>
                Die Inhalte dieser Website wurden mit größtmöglicher Sorgfalt erstellt. Für die Richtigkeit,
                Vollständigkeit und Aktualität der Inhalte kann jedoch keine Gewähr übernommen werden.
            </p>

            <h3 class="text-lg font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2">Haftung für Links</h3>

            <p>
                Diese Website enthält Links zu externen Websites Dritter, auf deren Inhalte kein Einfluss
                besteht.
                Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber
                verantwortlich.
                Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße
                überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.
            </p>

            <h3 class="text-lg font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2">Urheberrecht</h3>

            <p>
                Die durch den Seitenbetreiber erstellten Inhalte und Werke auf dieser Website unterliegen
                dem
                deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der
                Verwertung
                außerhalb der Grenzen des Urheberrechts bedürfen der schriftlichen Zustimmung des jeweiligen
                Autors
                bzw. Erstellers.
            </p>
        </article>
    </div>
</x-layouts::app>
