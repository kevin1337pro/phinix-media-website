# Eigenes Theme → Repository → WordPress.com

Stand 04.09.2026. Vom Nutzer bestätigt: Hosting bei WordPress.com; eigenes Theme und eigene Animation. Keine Slider-Revolution-Lizenz erforderlich.

## Architektur und aktueller Umfang

`themes/phinix-media/` ist ein eigenständiges WordPress-Block-Theme. Es enthält `style.css`, `theme.json`, Templates, Template-Teile, bearbeitbare Block-Patterns, lokal gespeicherte Bebas Neue und ein kleines JavaScript für die Hero-Szenen. Inhalte und Abschnitte können im Site-Editor angepasst werden. Der Editor ist Teil des Block-Theme-Konzepts. [WordPress: Block-Themes](https://wordpress.org/documentation/article/block-themes/)

Der aktuelle Theme-Stand ist ein **Vorabstand 0.2.0** mit vollständigem Startseitenentwurf. Darin: Hero, Websites, Branding, Print, Marketing/Content, Google-SEO, Screens, Projektplatzhalter, Kevin und Janine, Ablauf, FAQ und Kontaktplatzhalter. Hinzu kommen allgemeine Seiten-, Beitrags-, Archiv-/Index- und 404-Templates.

Die drei Hero-Szenen heißen Auftritt, Präsenz und Google. Sie sind per Mausklick und Tastatur umschaltbar. Reduzierte Bewegung wird respektiert; ohne JavaScript bleiben die Szenen lesbar. Die Fotos werden später ergänzt. Ein Original-Symbol-SVG ist dekorativ eingebunden. Die Header-Wortmarke wird in der mitgelieferten Bebas gesetzt; die gelieferten Wortbild-SVGs mit ungeklärter Bold-Schrift werden noch nicht verwendet.

Der Haupt-CTA führt zum vorbereiteten Kontaktabschnitt. **Dort führt ein Kontaktbutton zum bestehenden Formular auf https://phinix.media/ueber/.** Die Vorschau selbst sammelt keine Daten. Vor der Veröffentlichung muss der Platzhalter durch das WordPress.com-Formular und Janines bestätigte Empfängeradresse ersetzt werden.

## Repository-Struktur

```text
README.md
.gitignore
docs/
  01-konzept-und-stufenplan.md
  02-repository-und-wordpress.md
  03-status-und-materialliste.md
themes/phinix-media/
  style.css
  functions.php
  theme.json
  templates/
  parts/
  patterns/
  assets/
scripts/
  playground-blueprint.json
  package-theme.py
output/                       # lokale Release-Dateien; nicht in Git
.local/                       # lokale Testdaten; nicht in Git
```

Empfehlung: zunächst ein privates Repository `phinix-media-website` beim gewünschten Anbieter. Das private Repository wurde unter `kevin1337pro/phinix-media-website` angelegt. Nur Theme, Werkzeuge und freigegebene Projektdokumentation hochladen. Geschäftsunterlagen, Kundendaten, Zugangsdaten, Datenbankkopien und rohe Fotoarchive gehören nicht in dieses Repository.

## Schritt 1: Hosting-Tarif und Bestandsseite prüfen

1. In WordPress.com den aktuellen Tarif sowie die Menüpunkte für Theme-Upload und Hosting prüfen.
2. Vor Änderungen Bestand sichern: Inhalte, Medien, aktives Theme und relevante Einstellungen. Den vom Tarif unterstützten Backup-/Wiederherstellungsweg feststellen; ein Inhalts-XML allein ist kein vollständiges Backup.
3. Bestehende URLs, Navigation, Formularzustellung, Pflichtangaben und Suchmaschinenstatus erfassen.

Stand der offiziellen Dokumentation: Eigene Themes können auf Personal, Premium, Business und Commerce hochgeladen werden; direkte GitHub-Deployments sind Business und Commerce vorbehalten. Der tatsächliche Tarif ist noch unbekannt. Diese Möglichkeiten wurden am 04.09.2026 geprüft. [Theme-Upload](https://wordpress.com/support/themes/uploading-setting-up-custom-themes/) · [GitHub-Deployments](https://wordpress.com/support/github-deployments/)

## Schritt 2: Lokal bearbeiten und versionieren

Die Entwicklungsfassung verwendet WordPress Playground als isolierte lokale WordPress-Installation. Der Test-Blueprint aktiviert das Theme und erzeugt nur lokal gekennzeichnete Platzhalterseiten für Impressum und Datenschutz. Er ist nicht für die Produktion bestimmt.

```sh
npx --yes @wp-playground/cli@latest server \
  --mount='./themes/phinix-media:/wordpress/wp-content/themes/phinix-media' \
  --blueprint=scripts/playground-blueprint.json \
  --port=9400 --workers=1
```

Für reproduzierbare weitere Läufe die erfolgreich getestete CLI-Version festhalten beziehungsweise fest pinnen. Die Theme-Dateien selbst benötigen keine Node-Abhängigkeiten auf WordPress.com. [WordPress Playground CLI](https://wordpress.github.io/wordpress-playground/developers/local-development/wp-playground-cli/)

Ein lokales Git-Repository ist vorbereitet. Nach Einrichtung des Ziel-Repositories: den lokalen Arbeitsstand bei Änderungen committen, ein Remote mit der tatsächlich bestätigten URL verbinden und den Branch hochladen. Die unbekannte Remote-URL wird nicht geraten. Ein Push allein verändert WordPress noch nicht.

## Schritt 3: Inhalte im Site-Editor vervollständigen

1. Theme zunächst in einer Testumgebung aktivieren.
2. Unter Design → Editor das Startseiten-Template und Header/Footer öffnen.
3. Texte, Leistungen, Farben und Abstände überprüfen. Die Block-Klassen für Layout und Hero-Steuerung beibehalten.
4. Platzhalter durch Projektfotos und Porträts ersetzen. Aussagekräftige Alternativtexte für informative Bilder hinterlegen.
5. Kontaktplatzhalter entfernen, WordPress.com-Formular einsetzen, Empfängeradresse und Bestätigungsanzeige konfigurieren.
6. Bestehende Impressums- und Datenschutzinhalte erhalten beziehungsweise passend zum endgültigen Dienstumfang aktualisieren. Das Theme erzeugt keine fertigen Rechtstexte.
7. Unterseiten mit dem vorhandenen Seiten-Template anlegen und Inhalte ausarbeiten. Das Startseiten-Template allein enthält noch keine individuellen Leistungs-Unterseiten.

Wichtig: Änderungen im Site-Editor landen in der Datenbank und können Templates aus dem Theme überschreiben. Vor einem Release exportieren und bewusst in Theme-Dateien zurückführen. Sonst unterscheiden sich Repo und Website. Normale Seiten/Beiträge, Formularkonfiguration, Menüs und Medien müssen zusätzlich gesichert oder übertragen werden. [WordPress: Templates](https://developer.wordpress.org/themes/templates/templates/)

## Schritt 4: Installierbares Paket erstellen

```sh
python3 scripts/package-theme.py
```

Ergebnis: `output/phinix-media-0.2.0.zip`. Das Paket enthält direkt den Ordner `phinix-media` mit `style.css` und `templates/index.html`. Das komplette Repository-ZIP ist kein Theme-Paket. Schriftlizenz und benötigte Assets sind enthalten.

## Schritt 5A: Upload über WordPress.com

1. In der vorgesehenen Testsite Design → Themes → Theme hinzufügen/hochladen öffnen.
2. Das erzeugte Theme-ZIP auswählen und installieren.
3. Erst in der Testsite aktivieren; Inhalte, Navigation und Editor prüfen.
4. Fotos, Kontaktformular und Pflichtangaben vervollständigen.
5. Nach bestandenen Prüfungen das identische Release-Paket auf der Livesite installieren und aktivieren. Vorher aktuellen Wiederherstellungspunkt sichern.

Dieser Weg ist auch dann möglich, wenn keine direkte GitHub-Anbindung verfügbar ist. Das Repository bleibt die Quelle für den Theme-Code. [WordPress.com: Theme-Upload](https://wordpress.com/support/themes/uploading-setting-up-custom-themes/)

## Schritt 5B: Direkte GitHub-Anbindung, falls im Tarif verfügbar

1. Im WordPress.com-Hosting-Dashboard Deployments öffnen.
2. Das konkrete GitHub-Repository verbinden und Zugriff darauf beschränken.
3. Den ausgewählten Theme-Ordner auf `wp-content/themes/phinix-media` abbilden; nicht das komplette Repo ins Theme-Verzeichnis kopieren.
4. Test- und Produktionsziel klar trennen. Einen Deployment-Lauf zunächst manuell auslösen.
5. Änderungen nur aus einem geprüften Release-Stand veröffentlichen. Datenbank, Medien und Editor-Anpassungen gesondert berücksichtigen.

Erst nach Bekanntwerden von Tarif, Repo und Deployment-Einstellungen einen konkreten Workflow einrichten. Aktuell existiert kein aktiver automatischer Deployment-Workflow. [WordPress.com: GitHub](https://wordpress.com/support/github-deployments/)

## Schritt 6: Abnahme vor dem Launch

- Desktop, Tablet und Mobil: Navigation, keine horizontal abgeschnittenen Texte, gut lesbare Headlines, 200 % Zoom.
- Hero: Maus, Pfeiltasten, Home/End, reduzierte Bewegung und Verhalten ohne JavaScript prüfen.
- Formular: echte Testanfrage, erfolgreiche Zustellung an Janine, verständliche Bestätigung, Fehlerzustand und Spam-Schutz.
- Inhalte: echte Bilder, korrekte Rollen, nur freigegebene Kundenreferenzen, keine Platzhalter oder erfundenen Ergebnisse.
- WordPress: Startseite, reguläre Seite, Beitrag, Archiv und 404; Speichern und erneutes Öffnen im Editor.
- Suche: passende Titel/Beschreibungen, Hauptüberschrift, interne Links, Bildtexte, Sitemap; bestehende Artikel-URLs erhalten. Änderungen in einer gezielten Weiterleitungsliste dokumentieren.
- Zielwerte für die spätere Messung: LCP ≤ 2,5 s, INP ≤ 200 ms, CLS ≤ 0,1. Das sind Qualitätsziele, keine bisher gemessenen Werte. Nach realer Bildintegration messen.
- Rechtliche Inhalte und eingesetzte Datendienste passend zur tatsächlichen Website prüfen. Keine optionalen Tracker standardmäßig im Theme. Das Theme enthält keine externen Schriftanfragen.
- Rückfallweg: vorheriges Theme und betroffene Inhalte wiederherstellen können.

## Schritt 7: www.phinix.media und Veröffentlichung

Ziel ist die vom Nutzer gewünschte Hauptadresse `https://www.phinix.media`. WordPress.com-Domainzuordnung, HTTPS und Weiterleitung von `phinix.media` auf die gewählte Hauptadresse prüfen. Der öffentliche Abruf ohne www war erreichbar; ein einmaliger fehlgeschlagener www-Abruf beweist keinen DNS-Fehler. DNS nur ändern, wenn es tatsächlich erforderlich ist. Mail-DNS-Einträge erhalten.

Die letzte Prüfung erfolgt am echten Zielsystem. Nach Aktivierung Cache leeren, URL-Weiterleitungen und Formulare erneut prüfen und sicherstellen, dass die Livesite für Suchmaschinen freigegeben ist. Der lokale Test-Blueprint setzt absichtlich `blog_public=0`; diese Einstellung nicht auf die Produktion übertragen.

Ein Rückbau der Buchungs-App oder ein Umbau der Player-Infrastruktur ist nicht Teil dieses Website-Releases.
