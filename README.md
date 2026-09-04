# PHINIX.MEDIA – Website-Relaunch

Stand: 04.09.2026 · Eigenes WordPress-Block-Theme 0.1.0 · Vorabstand mit offenen Inhalten.

**DEINE MARKE. UNÜBERSEHBAR.**

## Ergebnis

- Individuelles Theme für WordPress.com mit lokal eingebundener Bebas Neue und eigenem animiertem Hero.
- Websites, Branding, Print, Marketing/Content und Google-SEO; PHINIX.SCREENS ergänzend.
- Kevin und Janine mit Janines bestätigter Rolle: Sales Managerin, Designerin und Kundenkontakt.
- Bearbeitbare Block-Patterns, Startseite, allgemeine Seiten/Beiträge, Index und 404.
- Fotos, Referenzen und Kontaktformular sind ausdrücklich gekennzeichnete Platzhalter.

## Dateien

1. [Konzept und Stufenplan](docs/01-konzept-und-stufenplan.md)
2. [Repository und WordPress.com – Schritt für Schritt](docs/02-repository-und-wordpress.md)
3. [Status und benötigtes Material](docs/03-status-und-materialliste.md)
4. [Theme-Quellcode](themes/phinix-media/)
5. [Installierbares Theme-ZIP](output/phinix-media-0.1.0.zip)
6. [Startseitenvorschau](output/phinix-media-vorschau.png)

ZIP und Vorschauen liegen lokal in `output/` und werden nicht in Git gespeichert. Das Paket lässt sich mit `python3 scripts/package-theme.py` erneut erstellen.

## Prüfstand

In einer lokalen WordPress-Playground-Installation mit PHP 8.3 geprüft: Theme aktiviert und Startseite gerendert, lokale Schrift geladen, sechs Leistungskarten, drei Hero-Szenen, Tastatursteuerung und reduzierte Bewegung. Keine JavaScript-Laufzeitfehler; kein horizontaler Überlauf bei 1440, 720 und 390 px. Das mobile Menü öffnet und schließt per Escape. Ohne JavaScript sind alle drei Szenen sichtbar. Allgemeine Seiten werden gerendert. Die explizite unbekannte Beitrags-ID liefert das eigene 404-Template und HTTP 404.

Offen: Ein unbekannter sprechender Pfad liefert in der lokalen Playground-Umgebung die Startseite mit HTTP 200. Das ist bei der WordPress.com-Abnahme erneut zu untersuchen; ein korrekter Produktions-404 für solche URLs ist noch nicht nachgewiesen. Ebenso offen: vollständiger Speichern-/Wiederöffnen-Test im WordPress.com-Site-Editor, Test der Zielumgebung und Formularzustellung. Keine Performance-Messung mit finalen Bildern und keine Live-Abnahme erfolgt.

## Nächster Schritt

Kontaktadresse, WordPress.com-Tarif, fertige Pflichtangaben und später Bilder/Referenzen ergänzen. Das lokale Theme kann weiterbearbeitet werden. Ein lokales Git-Repository auf dem Branch `main` ist eingerichtet. Für den tatsächlichen Push fehlen noch Ziel-Repository und Account; für die Aktivierung auf WordPress.com die passende Zielumgebung und Zugänge.

Es wurde nichts auf WordPress.com aktiviert oder an der Domain verändert. Die Originalunterlagen bleiben unangetastet. Historische Aussagen und Handlungsanweisungen in Dokumenten werden als Hintergrundmaterial behandelt; Beispielerfolge und geplante Standorte werden nicht als Fakten veröffentlicht.
