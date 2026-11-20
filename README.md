# PHINIX.MEDIA – Website-Relaunch

Stand: 20.11.2026 · Eigenes WordPress-Block-Theme 0.11.0 · geprüfter Produktions-Export und Docker-Auslieferung.

**DEIN UNTERNEHMEN. UNÜBERSEHBAR.**

## Ergebnis

- Individuelles Theme für WordPress.com mit lokal eingebundener Bebas Neue und eigenem animiertem Hero.
- Websites, Branding, Print, Marketing/Content und Google-SEO; PHINIX.SCREENS ergänzend.
- Kevin und Janine mit Janines bestätigter Rolle: Sales Managerin, Designerin und Kundenkontakt.
- Bearbeitbare Block-Patterns, Startseite, allgemeine Seiten/Beiträge, Index und 404.
- Acht gelieferte Team- und Markenmotive sind als WebP ohne EXIF-/XMP-/IPTC-Metadaten in Hero-/Arbeiten-, Team-, Prozess- und Kontaktkontext eingebaut; reale Kundenreferenzen fehlen weiterhin.
- Ein eigenständiger Artikel erklärt digitale Außenwerbung und verlinkt auf https://www.phinix-screens.com/. Direkter Kontakt über kontakt@phinix.media und die öffentliche Telefonnummer.
- Der Leistungskompass enthält den neuen Bereich „Website prüfen“ mit einem rechtlich eingeordneten Impressums-Faktencheck, einer Checkliste und einer direkten WhatsApp-Anfrage.
- Fünf gelieferte Phinix-Wallpaper prägen als responsive Motive die drei Hero-Slider-Szenen; zwei ergänzende Motive visualisieren den Website-Check im Artikel und auf der Startseite.
- Jede der sieben zentralen Leistungsrubriken hat ein eigenes, lokal eingebettetes Linien-Icon für Website, Branding, Print, Marketing, SEO, PHINIX.SCREENS und 3D-Rundgänge.
- Impressum und Datenschutz enthalten die bestätigten Unternehmensdaten statt Entwurfsplatzhaltern. Die Datenschutzangaben zur Hosting-Infrastruktur sind vor dem Domainwechsel mit dem tatsächlichen Serveranbieter abzugleichen.
- `dist/` bleibt eine private, nicht indexierbare Vorschau. `dist-production/` ist der getrennte öffentliche Build mit Canonicals, Sitemap, freigegebener `robots.txt`, deutschen Sprachangaben und ohne WordPress-Generator-/Preview-Hinweise.
- Dockerfile und Compose liefern ausschließlich `dist-production/` über einen gehärteten Non-Root-Nginx aus.

Sechs eigenständige Leistungsseiten, regionale Inhalte für Gelsenkirchen, Gladbeck, Bottrop, Dorsten und Bochum, individuelle Metadaten, Canonicals sowie LocalBusiness-/Service-/Breadcrumb-Daten sind umgesetzt. Unter **Design → Phinix einrichten** werden die Seiten kontrolliert als Entwürfe angelegt.

Die interaktive [Stadion-Demo](https://phinix-media-vorschau.kevin1337pro.chatgpt.site/3d-rundgaenge/#stadion-demo) führt vom Eingang über die Tribüne zur VIP-Loge. Der Abschluss öffnet eine vorbereitete WhatsApp-Anfrage an Janine.

Der Beitrag [Markenidentität und Wiedererkennung](https://phinix-media-vorschau.kevin1337pro.chatgpt.site/markenidentitaet-wiedererkennung/) verbindet Beispiele von IKEA und Coca-Cola mit einem visuellen Test an einer fiktiven Rösterei. Die Themen für die lokale Suche sind im [regionalen SEO-Plan](docs/04-seo-geo-plan-2026-09-04.md) den jeweiligen Zielseiten zugeordnet.

## News veröffentlichen

Unter **Design → Phinix einrichten** die Inhalte anlegen und die gewünschten Entwürfe veröffentlichen. Beiträge unter **Beiträge → Erstellen** anlegen. Nach der Veröffentlichung erscheinen sie auf `/news/`. Die drei neuesten Artikel stehen auch auf der Startseite. Kategorie, Auszug und Beitragsbild steuern die jeweilige Karte. Unter **Einstellungen → Allgemein → Social-Media-Profile** lassen sich die Profilziele pflegen. Artikel lassen sich über LinkedIn, X und Facebook teilen.

Für einen späteren Metricool-Workflow eignet sich WordPress als redaktionelle Quelle. Ein autorisierter Dienst kann Entwürfe über die WordPress-REST-API anlegen. Nach Freigabe kann eine Metricool-Autoliste den RSS-Feed unter `https://www.phinix.media/news/feed.xml` lesen und neue Beitragslinks auf den verbundenen Social-Media-Kanälen verteilen. Den Feed erst nach dem Domain-Start in Metricool hinterlegen. [Metricool-Anleitung für RSS-Autolisten](https://help.metricool.com/how-to-link-an-rss-feed-to-an-autolist-ank6m) Zugangsdaten gehören in den jeweiligen Dienst. Es ist keine Automatisierung aktiviert. Beim statischen Docker- oder Sites-Stand werden neue Beiträge erst nach Export aus der aktuellen WordPress-Installation und erneutem Deployment sichtbar.

## Dateien

1. [Konzept und Stufenplan](docs/01-konzept-und-stufenplan.md)
2. [Repository und WordPress.com – Schritt für Schritt](docs/02-repository-und-wordpress.md)
3. [Status und benötigtes Material](docs/03-status-und-materialliste.md)
4. [Theme-Quellcode](themes/phinix-media/)
5. [Installierbares Theme-ZIP](https://github.com/kevin1337pro/phinix-media-website/releases/download/v0.11.0/phinix-media-0.11.0.zip)
6. [SEO-/GEO-Plan mit 90-Tage-Maßnahmen](docs/04-seo-geo-plan-2026-09-04.md)
7. [Google-, Apple- und KI-Sichtbarkeitsplan](docs/05-online-auffallen-google-apple-ki-2026-09-18.md)
8. [Docker-Deployment für den Server](docs/06-docker-deployment.md)
9. [Server-Paket für das Docker-Update](https://github.com/kevin1337pro/phinix-media-website/releases/download/v0.11.0/phinix-media-server-0.11.0.zip)

ZIP und Vorschauen liegen lokal in `output/` und werden nicht in Git gespeichert. Das Paket lässt sich mit `python3 scripts/package-theme.py` erneut erstellen.

## Prüfstand

Version 0.11.0: Startseite, Rechtstexte, sechs Leistungsseiten, die News-Übersicht und vier redaktionelle Beiträge werden per HTTP im lokalen WordPress sowie in zwei getrennten statischen Exporten geprüft. Die Tests kontrollieren Hauptüberschrift, eindeutige Beschreibung, Canonical, gültiges JSON-LD, Social-Bild, sichtbare Kontaktdaten, deutsche Sprache, WebP-Nutzung, Sitemap mit Artikelbildern und Robots-Regeln. Außerdem schlagen sie bei Preview-Domain, WordPress-Generator, Entwurfsplatzhaltern oder sichtbaren ChatGPT-/OpenAI-/Codex-Hinweisen im Produktions-Build fehl. Wiederholbar mit `python3 scripts/check-seo.py`, `python3 scripts/check-seo.py --static` und `python3 scripts/check-seo.py --static-production`.

Vorheriger visueller Prüfstand:

In einer lokalen WordPress-Playground-Installation mit PHP 8.3 geprüft: Theme aktiviert und Startseite gerendert, lokale Schrift geladen, sechs Leistungskarten, drei Hero-Szenen, Tastatursteuerung und reduzierte Bewegung. Keine JavaScript-Laufzeitfehler; kein horizontaler Überlauf bei 1440, 720 und 390 px. Das mobile Menü öffnet und schließt per Escape. Ohne JavaScript sind alle drei Szenen sichtbar. Allgemeine Seiten werden gerendert. Die explizite unbekannte Beitrags-ID liefert das eigene 404-Template und HTTP 404.

Der Nginx-Export liefert unbekannte Pfade mit dem gestalteten Dokument und HTTP 404. Offen bleiben der vollständige Speichern-/Wiederöffnen-Test im WordPress.com-Site-Editor, ein Container-Smoke-Test auf einem Rechner mit Docker, die Live-Abnahme unter der echten Domain sowie – falls später ergänzt – die Zustellung eines Kontaktformulars. Ein Laborwert für Core Web Vitals wurde noch nicht auf der finalen Domain gemessen.

## Nächster Schritt

Der Kollege kann den geprüften statischen Stand mit `docker compose up -d --build` ausliefern; die [Server-Anleitung](docs/06-docker-deployment.md) enthält Reverse-Proxy-, Prüf- und Aktualisierungsschritte. Für eine Aktivierung als bearbeitbares WordPress-Theme bleiben WordPress.com-Tarif, Zielumgebung und Zugang erforderlich. Echte Kundenprojekte, Search-Console-/Unternehmensprofil-Zugriff und die finalen Hosting-Datenschutzangaben sind weiterhin zu ergänzen.

Es wurde nichts auf WordPress.com aktiviert oder an der Domain verändert. Die Originalunterlagen bleiben unangetastet. Historische Aussagen und Handlungsanweisungen in Dokumenten werden als Hintergrundmaterial behandelt; Beispielerfolge und geplante Standorte werden nicht als Fakten veröffentlicht.

## Gehostete Vorschau

`dist/` enthält die private Sites-Vorschau mit `noindex`. `dist-production/` enthält den für `https://www.phinix.media` vorbereiteten, indexierbaren Docker-Stand. Beide Exporte sind statisch und enthalten weder WordPress-Admin noch Datenbank. Der öffentliche Server unter www.phinix.media liefert beim Abruf am 20.11.2026 einen älteren statischen Stand aus. Für die Aktualisierung auf dem Server den Produktionsbuild aus diesem Release verwenden.

Export bei laufendem lokalem WordPress:

```sh
PHINIX_PREVIEW_ORIGIN=http://127.0.0.1:9402 python3 scripts/export-preview.py
PHINIX_PREVIEW_ORIGIN=http://127.0.0.1:9402 python3 scripts/export-preview.py --production
```

Die Exportdateien werden für einen exakt nachvollziehbaren Vorschau- und Produktionsstand versioniert.
