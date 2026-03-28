<x-layouts::app titleAddition="{{ __('Privacy') }}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 relative z-10">
        <article class="text-zinc-600 dark:text-zinc-400 space-y-6 text-sm">
            <x-ui.heading-1 title="Datenschutzerklärung"></x-ui.heading-1>

            <x-ui.heading-2 title="1. Verantwortlicher" variant="decent" />

            <p>
                <strong>{{ $first_name }} {{ $last_name }}</strong><br>
                {{ $street }}<br>
                {{ $postal_code }} {{ $city }}<br>
                {{ $country }}<br>
                E-Mail: <a href="mailto:{{ $email }}">{{ $email }}</a>
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="2. Allgemeines zur Datenverarbeitung" variant="decent" />

            <h3 class="text-lg
                    font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2">Umfang der
                Verarbeitung
                personenbezogener Daten</h3>

            <p>
                Personenbezogene Daten werden auf dieser Website nur im technisch notwendigen Umfang erhoben.
                Eine Weitergabe an Dritte erfolgt nicht, sofern dies nicht ausdrücklich erwähnt wird.
            </p>

            <h3 class="text-lg font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2">Rechtsgrundlage</h3>

            <p>
                Soweit für Verarbeitungsvorgänge personenbezogener Daten eine Einwilligung eingeholt wird, dient
                Art. 6 Abs. 1 lit. a DSGVO als Rechtsgrundlage. Für die Verarbeitung zur Wahrung berechtigter
                Interessen dient Art. 6 Abs. 1 lit. f DSGVO als Rechtsgrundlage.
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="3. Hosting und Server-Logfiles" variant="decent" />

            <p>
                Diese Website wird gehostet bei:
            </p>
            <p>
                <strong>Strato AG</strong><br>
                Otto-Ostrowski-Straße 7<br>
                10249 Berlin<br>
                <a href="https://www.strato.de" target="_blank" rel="noopener noreferrer">https://www.strato.de</a>
            </p>
            <p>
                Beim Aufruf der Website werden durch den Hosting-Anbieter automatisch sogenannte
                Server-Logfiles
                erfasst, die folgende Informationen enthalten können:
            </p>

            <ul class="list-disc list-inside space-y-1 pl-2">
                <li>IP-Adresse des anfragenden Geräts</li>
                <li>Datum und Uhrzeit des Zugriffs</li>
                <li>Name und URL der abgerufenen Datei</li>
                <li>Website, von der aus der Zugriff erfolgt (Referrer-URL)</li>
                <li>Verwendeter Browser und ggf. das Betriebssystem</li>
            </ul>

            <p>
                Diese Daten werden ausschließlich zur Sicherstellung eines störungsfreien Betriebs der
                Website
                verwendet und nicht mit anderen Datenquellen zusammengeführt. Rechtsgrundlage ist
                Art. 6 Abs. 1 lit. f DSGVO.
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="4. Kontaktaufnahme per E-Mail" variant="decent" />

            <p>
                Wenn du per E-Mail Kontakt aufnimmst, werden die übermittelten Daten (E-Mail-Adresse, Name,
                Nachrichteninhalt) zum Zweck der Bearbeitung der Anfrage gespeichert. Eine Weitergabe an Dritte
                findet nicht statt. Die Daten werden gelöscht, sobald die Anfrage abschließend bearbeitet wurde.
                Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO.
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="5. Externe Inhalte und Dienste" variant="decent" />

            <h3 class="text-lg font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2"> Social Media</h3>

            <p>
                Diese Website kann Links zu Social-Media-Plattformen (z. B. Instagram, Facebook,
                YouTube) enthalten.
                Beim Anklicken dieser Links verlässt du diese Website. Es gelten die
                Datenschutzbestimmungen des
                jeweiligen Anbieters. Es werden keine Social-Media-Plugins eingebunden, die
                automatisch Daten
                übertragen.
            </p>

            <h3 class="text-lg font-medium text-zinc-800 dark:text-zinc-200 mt-6 mb-2">
                Eingebettete Inhalte (z. B. YouTube-Videos, Spotify)
            </h3>

            <p>
                Sofern auf dieser Website externe Inhalte eingebettet sind, kann der jeweilige
                Anbieter beim Laden
                der Seite Daten (u. a. deine IP-Adresse) erheben. Nähere Informationen findest du in
                den
                Datenschutzbestimmungen des jeweiligen Anbieters.
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="6. Cookies" variant="decent" />

            <p>
                Diese Website verwendet keine Cookies, sofern dies nicht technisch erforderlich ist. Es werden
                keine Tracking- oder Analyse-Cookies eingesetzt.
            </p>

            <hr class="border-zinc-200 dark:border-zinc-700 my-8">

            <x-ui.heading-2 title="7. Deine Rechte als betroffene Person" variant="decent" />

            <p>Du hast gegenüber dem Verantwortlichen folgende Rechte:</p>

            <ul class="list-disc list-inside space-y-1 pl-2">
                <li>
                    <strong>Auskunft</strong> (Art. 15 DSGVO): Recht auf Auskunft über gespeicherte Daten
                </li>
                <li>
                    <strong>Berichtigung</strong> (Art. 16 DSGVO): Recht auf Korrektur unrichtiger Daten
                </li>
                <li>
                    <strong>Löschung</strong> (Art. 17 DSGVO): Recht auf Löschung der Daten
                </li>
                <li>
                    <strong>Einschränkung</strong> (Art. 18 DSGVO): Recht auf Einschränkung der Verarbeitung
                </li>
                <li>
                    <strong>Widerspruch</strong> (Art. 21 DSGVO): Recht auf Widerspruch gegen die Verarbeitung
                </li>
                <li>
                    <strong>Datenübertragbarkeit</strong> (Art. 20 DSGVO): Recht auf Herausgabe der Daten in einem
                    gängigen Format
                </li>
            </ul>

            <p>
                Zur Ausübung deiner Rechte wende dich an:
                <a href="mailto:{{ $email }}">{{ $email }}</a>
            </p>

            <p>
                Zudem hast du das Recht, dich bei einer Datenschutz-Aufsichtsbehörde zu
                beschweren.
                Die zuständige Behörde für Brandenburg ist:
            </p>

            <p>
                <strong>Die Landesbeauftragte für den Datenschutz und für das Recht auf
                    Akteneinsicht Brandenburg
                    (LDA)</strong><br>
                Stahnsdorfer Damm 77<br>
                14532 Kleinmachnow<br>
                <a href="https://www.lda.brandenburg.de" target="_blank"
                    rel="noopener noreferrer">www.lda.brandenburg.de</a>
            </p>
        </article>
    </div>
</x-layouts::app>
