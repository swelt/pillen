# Installation des Pillen WordPress Plugins

## Schritt 1: Plugin installieren

### Option A: Direkter Upload
1. Komprimieren Sie den Ordner `pillen-wordpress` als ZIP-Datei
2. Gehen Sie in Ihr WordPress Backend → Plugins → Installieren
3. Klicken Sie auf "Plugin hochladen"
4. Wählen Sie die ZIP-Datei aus
5. Klicken Sie auf "Jetzt installieren"
6. Aktivieren Sie das Plugin

### Option B: FTP/SFTP Upload
1. Laden Sie den Ordner `pillen-wordpress` in Ihr WordPress-Verzeichnis hoch:
   ```
   /wp-content/plugins/pillen-wordpress/
   ```
2. Gehen Sie ins WordPress Backend → Plugins
3. Aktivieren Sie "Pillen Database"

## Schritt 2: Daten importieren

Nach der Aktivierung:

1. Gehen Sie zu **Pillen → Import** im WordPress Admin-Menü
2. Klicken Sie auf **"Bestehende Daten importieren"** um die Daten aus `static/pillen.json` zu importieren

   **ODER**

   Laden Sie eine eigene JSON-Datei hoch

3. Warten Sie, bis der Import abgeschlossen ist
4. Sie sollten eine Erfolgsmeldung sehen: "X Pillen erfolgreich importiert"

## Schritt 3: Seite erstellen

1. Erstellen Sie eine neue Seite: **Seiten → Erstellen**
2. Geben Sie der Seite einen Titel, z.B. "Pillenwarnungen"
3. Fügen Sie den Shortcode ein:
   ```
   [pillen_database]
   ```
4. Veröffentlichen Sie die Seite

## Schritt 4: Testen

Besuchen Sie die neu erstellte Seite und testen Sie:
- ✓ Farbfilter funktionieren
- ✓ Suchfeld funktioniert
- ✓ Klick auf Pillen öffnet Details-Modal
- ✓ Alle Bilder werden angezeigt

## Bilder importieren

⚠️ **Wichtig**: Damit die Bilder korrekt importiert werden, muss der Ordner `static/pillen/` mit allen Bildern vorhanden sein.

Der Import-Prozess:
1. Sucht Bilder in `../static/pillen/` (relativ zum Plugin-Verzeichnis)
2. Kopiert sie in den WordPress Media Library
3. Verknüpft sie mit den entsprechenden Pillen

Falls die Bilder nicht automatisch importiert werden:
1. Prüfen Sie, ob der Pfad `static/pillen/` existiert
2. Passen Sie ggf. den Pfad in `includes/class-pillen-importer.php` Zeile 142 an

## Eigene Pillen hinzufügen

1. Gehen Sie zu **Pillen → Neu hinzufügen**
2. Füllen Sie alle Felder aus:
   - Titel: Name der Pille
   - Taxonomies: Farbe, Ort, Logo, Quelle
   - Details: Datum, Durchmesser, Dicke, etc.
   - Wirkstoffe: Klicken Sie auf "Wirkstoff hinzufügen"
   - Bilder: Laden Sie Bilder hoch (erstes Bild = Hauptbild)
3. Veröffentlichen

## Mehrsprachigkeit

Das Plugin unterstützt DE/FR/EN.

### Übersetzungen hinzufügen

1. Kopieren Sie `languages/pillen.pot` zu `languages/pillen-de_DE.po`
2. Übersetzen Sie die Strings
3. Kompilieren Sie mit: `msgfmt pillen-de_DE.po -o pillen-de_DE.mo`
4. Die existierenden Übersetzungen aus `translations/` können wiederverwendet werden

## Troubleshooting

### Pillen werden nicht angezeigt
- Prüfen Sie die Browser-Konsole auf JavaScript-Fehler
- Stellen Sie sicher, dass jQuery geladen ist
- Prüfen Sie, ob die REST API funktioniert: `/wp-json/pillen/v1/pills`

### Import schlägt fehl
- Erhöhen Sie das PHP Memory Limit in `wp-config.php`:
  ```php
  define('WP_MEMORY_LIMIT', '256M');
  ```
- Erhöhen Sie das Upload-Limit für große JSON-Dateien

### Bilder werden nicht angezeigt
- Prüfen Sie die Pfade in `class-pillen-importer.php`
- Stellen Sie sicher, dass der `static/pillen/` Ordner existiert und lesbar ist
- Prüfen Sie die Dateiberechtigungen

## Support

Bei Problemen:
1. Aktivieren Sie WP_DEBUG in wp-config.php
2. Prüfen Sie die WordPress Debug-Logs
3. Kontaktieren Sie: http://www.mindzone.info
