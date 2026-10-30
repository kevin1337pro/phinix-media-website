# PHINIX.MEDIA mit Docker ausliefern

Der Container liefert ausschließlich den geprüften statischen Produktions-Export aus `dist-production/` aus. WordPress, PHP und eine Datenbank werden auf dem Zielserver nicht benötigt. Formulare oder andere dynamische WordPress-Funktionen sind in diesem Export nicht enthalten.

Der getrennte Ordner `dist/` bleibt die private Sites-Vorschau und enthält absichtlich `noindex` sowie ein sperrendes `robots.txt`. Das Dockerfile kann diesen Vorschauordner nicht versehentlich ausliefern.

## Benötigte Dateien

Am einfachsten klont der Server das öffentliche Repository. Beim Versand als Ordner müssen mindestens diese Dateien enthalten sein:

- `Dockerfile`
- `docker-compose.yml`
- `.dockerignore`
- `docker/nginx.conf`
- der vollständige Ordner `dist-production/`

## Start auf dem Server

Voraussetzung sind Docker Engine und Docker Compose v2. Der geprüfte Ordner `dist-production/` ist bereits im Repository enthalten; auf dem Server ist deshalb weder Node.js noch WordPress nötig.

```bash
cp .env.example .env
docker compose config
docker compose build --pull
docker compose up -d
docker compose ps
```

Soll der statische Stand später aus einem geänderten Theme neu erzeugt werden, muss vorher lokal der WordPress Playground laufen. Der Produktions-Export wird anschließend bewusst getrennt erstellt und geprüft:

```bash
PHINIX_PREVIEW_ORIGIN=http://127.0.0.1:9402 python3 scripts/export-preview.py --production
python3 scripts/check-seo.py --static-production
```

Vor dem Build lässt sich die Indexierungsfreigabe kontrollieren:

```bash
python3 scripts/check-seo.py --static-production
grep -R "noindex" dist-production --include='*.html' || true
cat dist-production/robots.txt
```

Nur `dist-production/404.html` darf dabei `noindex` enthalten. `robots.txt` muss Crawler zulassen und auf `https://www.phinix.media/sitemap.xml` verweisen.

Die Standardkonfiguration bindet den Container an `127.0.0.1:8080`. Ein vorhandener Nginx-, Caddy- oder Traefik-Reverse-Proxy kann die Domain mit TLS dorthin weiterleiten. Für einen bewusst direkt erreichbaren Port kann in `.env` `PHINIX_BIND=0.0.0.0` gesetzt werden; TLS sollte trotzdem am Reverse Proxy enden.

## Prüfung

```bash
curl -I http://127.0.0.1:8080/healthz
curl -I http://127.0.0.1:8080/
curl -I http://127.0.0.1:8080/branding/
curl -I http://127.0.0.1:8080/nicht-vorhanden
```

Erwartet werden `204` für `/healthz`, `200` für die Start- und Leistungsseite sowie `404` mit dem gestalteten Fehlerdokument für den unbekannten Pfad.

## Aktualisierung

```bash
git pull --ff-only
docker compose build --pull
docker compose up -d
docker image prune -f
```

Der Container läuft ohne Root-Rechte, besitzt keine Linux-Capabilities, nutzt ein schreibgeschütztes Dateisystem und schreibt nur in ein kleines temporäres RAM-Dateisystem. Bilder, Fonts, CSS und JavaScript erhalten lange Browser-Caches; HTML wird bei jedem Aufruf neu validiert. Gzip ist für Textdateien aktiv.

