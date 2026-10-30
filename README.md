# PHINIX.MEDIA – Website-Relaunch

Stand: 30.10.2026 · Eigenes WordPress-Block-Theme 0.8.0 · geprüfter Produktions-Export und Docker-Auslieferung.

**DEIN UNTERNEHMEN. UNÜBERSEHBAR.**

## Ergebnis

- Individuelles Theme für WordPress.com mit lokal eingebundener Bebas Neue und eigenem animiertem Hero.
- Websites, Branding, Print, Marketing/Content und Google-SEO; PHINIX.SCREENS ergänzend.
- Kevin und Janine mit Janines bestätigter Rolle: Sales Managerin, Designerin und Kundenkontakt.
- Bearbeitbare Block-Patterns, Startseite, allgemeine Seiten/Beiträge, Index und 404.
- Acht gelieferte Team- und Markenmotive sind als WebP ohne EXIF-/XMP-/IPTC-Metadaten in Hero-/Arbeiten-, Team-, Prozess- und Kontaktkontext eingebaut; reale Kundenreferenzen fehlen weiterhin.
- Ein eigenständiger Artikel erklärt digitale Außenwerbung und verlinkt auf https://www.phinix-screens.com/. Direkter Kontakt über kontakt@phinix.media und die öffentliche Telefonnummer.
- Der Leistungskompass enthält den neuen Bereich „Website prüfen“ mit einem rechtlich eingeordneten Impressums-Faktencheck, einem externen Complioneer-Check und einer direkten WhatsApp-Anfrage.
- Fünf gelieferte Phinix-Wallpaper prägen als responsive Motive die drei Hero-Slider-Szenen; zwei ergänzende Motive visualisieren den Website-Check im Artikel und auf der Startseite.
- Jede der sechs zentralen Leistungsrubriken hat ein eigenes, lokal eingebettetes Linien-Icon für Website, Branding, Print, Marketing, SEO und PHINIX.SCREENS.
- Impressum und Datenschutz enthalten die bestätigten Unternehmensdaten statt Entwurfsplatzhaltern. Die Datenschutzangaben zur Hosting-Infrastruktur sind vor dem Domainwechsel mit dem tatsächlichen Serveranbieter abzugleichen.
- `dist/` bleibt eine private, nicht indexierbare Vorschau. `dist-production/` ist der getrennte öffentliche Build mit Canonicals, Sitemap, freigegebener `robots.txt`, deutschen Sprachangaben und ohne WordPress-Generator-/Preview-Hinweise.
- Dockerfile und Compose liefern ausschließlich `dist-production/` über einen gehärteten Non-Root-Nginx aus.

Fünf eigenständige Leistungsseiten, regionale Inhalte für Gelsenkirchen und Gladbeck, individuelle Metadaten, Canonicals sowie LocalBusiness-/Service-/Breadcrumb-Daten sind umgesetzt. Unter **Design → Phinix einrichten** werden die Seiten kontrolliert als Entwürfe angelegt.

## Dateien

1. [Konzept und Stufenplan](docs/01-konzept-und-stufenplan.md)
2. [Repository und WordPress.com – Schritt für Schritt](docs/02-repository-und-wordpress.md)
3. [Status und benötigtes Material](docs/03-status-und-materialliste.md)
4. [Theme-Quellcode](themes/phinix-media/)
5. [Installierbares Theme-ZIP](output/phinix-media-0.8.0.zip)
6. [SEO-/GEO-Plan mit 90-Tage-Maßnahmen](docs/04-seo-geo-plan-2026-09-04.md)
7. [Google-, Apple- und KI-Sichtbarkeitsplan](docs/05-online-auffallen-google-apple-ki-2026-09-18.md)
8. [Docker-Deployment für den Server](docs/06-docker-deployment.md)
9. Theme-Download: GitHub-Release `v0.8.0` nach Veröffentlichung.

ZIP und Vorschauen liegen lokal in `output/` und werden nicht in Git gespeichert. Das Paket lässt sich mit `python3 scripts/package-theme.py` erneut erstellen.

## Prüfstand

Version 0.8.0: Startseite, Rechtstexte, fünf Leistungsseiten und beide Ratgeber werden per HTTP im lokalen WordPress sowie in zwei getrennten statischen Exporten geprüft. Die Tests kontrollieren Hauptüberschrift, eindeutige Beschreibung, Canonical, gültiges JSON-LD, Social-Bild, sichtbare Kontaktdaten, deutsche Sprache, WebP-Nutzung, Sitemap und Robots-Regeln. Außerdem schlagen sie bei Preview-Domain, WordPress-Generator, Entwurfsplatzhaltern oder sichtbaren ChatGPT-/OpenAI-/Codex-Hinweisen im Produktions-Build fehl. Wiederholbar mit `python3 scripts/check-seo.py`, `python3 scripts/check-seo.py --static` und `python3 scripts/check-seo.py --static-production`.

Vorheriger visueller Prüfstand:

In einer lokalen WordPress-Playground-Installation mit PHP 8.3 geprüft: Theme aktiviert und Startseite gerendert, lokale Schrift geladen, sechs Leistungskarten, drei Hero-Szenen, Tastatursteuerung und reduzierte Bewegung. Keine JavaScript-Laufzeitfehler; kein horizontaler Überlauf bei 1440, 720 und 390 px. Das mobile Menü öffnet und schließt per Escape. Ohne JavaScript sind alle drei Szenen sichtbar. Allgemeine Seiten werden gerendert. Die explizite unbekannte Beitrags-ID liefert das eigene 404-Template und HTTP 404.

Der Nginx-Export liefert unbekannte Pfade mit dem gestalteten Dokument und HTTP 404. Offen bleiben der vollständige Speichern-/Wiederöffnen-Test im WordPress.com-Site-Editor, ein Container-Smoke-Test auf einem Rechner mit Docker, die Live-Abnahme unter der echten Domain sowie – falls später ergänzt – die Zustellung eines Kontaktformulars. Ein Laborwert für Core Web Vitals wurde noch nicht auf der finalen Domain gemessen.

## Nächster Schritt

Der Kollege kann den geprüften statischen Stand mit `docker compose up -d --build` ausliefern; die [Server-Anleitung](docs/06-docker-deployment.md) enthält Reverse-Proxy-, Prüf- und Aktualisierungsschritte. Für eine Aktivierung als bearbeitbares WordPress-Theme bleiben WordPress.com-Tarif, Zielumgebung und Zugang erforderlich. Echte Kundenprojekte, Search-Console-/Unternehmensprofil-Zugriff und die finalen Hosting-Datenschutzangaben sind weiterhin zu ergänzen.

Es wurde nichts auf WordPress.com aktiviert oder an der Domain verändert. Die Originalunterlagen bleiben unangetastet. Historische Aussagen und Handlungsanweisungen in Dokumenten werden als Hintergrundmaterial behandelt; Beispielerfolge und geplante Standorte werden nicht als Fakten veröffentlicht.

## Gehostete Vorschau

`dist/` enthält die private Sites-Vorschau mit `noindex`. `dist-production/` enthält den für `https://www.phinix.media` vorbereiteten, indexierbaren Docker-Stand. Beide Exporte sind statisch und enthalten weder WordPress-Admin noch Datenbank. Die bestehende produktive WordPress.com-Seite und die Domainzuordnung werden durch einen Git-Push nicht verändert.

Export bei laufendem lokalem WordPress:

```sh
PHINIX_PREVIEW_ORIGIN=http://127.0.0.1:9402 python3 scripts/export-preview.py
PHINIX_PREVIEW_ORIGIN=http://127.0.0.1:9402 python3 scripts/export-preview.py --production
```

Die Exportdateien werden für einen exakt nachvollziehbaren Vorschau- und Produktionsstand versioniert.
