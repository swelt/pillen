# Pillen Database WordPress Plugin

Das kolorimetrische Verzeichnis kleiner Pillen - Ein WordPress Plugin für Pillenwarnungen im Rahmen der Harm Reduction.

## Beschreibung

Dieses WordPress Plugin ermöglicht es, Pillen-Warnungen direkt im WordPress-Backend zu verwalten und auf der Website darzustellen. Es bietet eine benutzerfreundliche Oberfläche mit Farbfiltern, Suchfunktion und detaillierten Informationen zu jeder Pille.

## Features

- ✅ Custom Post Type für Pillen
- ✅ Taxonomies für Farbe, Ort, Logo und Quelle
- ✅ Detaillierte Metadaten (Datum, Durchmesser, Dicke, Gewicht, Bruchrille, Inhalt)
- ✅ Mehrere Bilder pro Pille
- ✅ Interaktive Frontend-Darstellung mit Farbfiltern
- ✅ Suchfunktion nach Logo/Namen
- ✅ REST API für Ajax-basierte Filterung
- ✅ JSON Import-Funktion für bestehende Daten
- ✅ Mehrsprachigkeit (DE/FR/EN)
- ✅ Responsive Design

## Installation

1. Laden Sie den Ordner `pillen-wordpress` in Ihr WordPress `wp-content/plugins/` Verzeichnis hoch
2. Aktivieren Sie das Plugin über das WordPress Admin-Menü unter 'Plugins'
3. Gehen Sie zu 'Pillen' > 'Import' um bestehende Daten zu importieren

## Verwendung

### Shortcode

Fügen Sie den folgenden Shortcode auf jeder Seite oder jedem Beitrag ein:

```
[pillen_database]
```

Für eine eingebettete Version ohne Header/Footer:

```
[pillen_database embedded="true"]
```

### Backend

1. **Neue Pille hinzufügen**: Klicken Sie auf 'Pillen' > 'Neu hinzufügen'
2. **Daten importieren**: Gehen Sie zu 'Pillen' > 'Import' und laden Sie eine JSON-Datei hoch
3. **Pillen verwalten**: Unter 'Pillen' > 'Alle Pillen' können Sie alle Einträge sehen und bearbeiten

### JSON Import

Das Plugin kann Pillen aus einer JSON-Datei importieren. Das erwartete Format:

```json
[
  {
    "name": "Mitsubishi",
    "farbe": "türkis",
    "logo": "Mitsubishi",
    "ort": "Zürich",
    "quelle": "Saferparty!",
    "datum": "26.09.2014",
    "durchmesser": "9.3 mm",
    "dicke": "3.6 mm",
    "gewicht": "262.7 mg",
    "bruchrille": "Nein",
    "inhalt": "35.1 mg m-CPP + 8.9 mg Metoclopramid",
    "composition": {
      "m-cpp": "35.1 mg"
    },
    "colorz": ["#8caa95", "#536e58"],
    "images": ["bild1.jpg", "bild2.jpg"]
  }
]
```

## REST API

Das Plugin stellt folgende Endpoints zur Verfügung:

- `GET /wp-json/pillen/v1/pills` - Alle Pillen abrufen (mit optionalen Filtern)
- `GET /wp-json/pillen/v1/filters` - Verfügbare Filter abrufen

### Parameter

- `farbe` - Nach Farbe filtern
- `ort` - Nach Ort filtern
- `logo` - Nach Logo filtern
- `search` - Textsuche

## Entwicklung

### Dateistruktur

```
pillen-wordpress/
├── pillen.php                          # Haupt-Plugin-Datei
├── includes/
│   ├── class-pillen-post-type.php      # Custom Post Type
│   ├── class-pillen-taxonomies.php     # Taxonomies
│   ├── class-pillen-meta-boxes.php     # Meta Boxes
│   ├── class-pillen-importer.php       # JSON Importer
│   ├── class-pillen-rest-api.php       # REST API
│   └── class-pillen-i18n.php           # Internationalisierung
├── admin/
│   └── class-pillen-admin.php          # Admin-Interface
├── public/
│   ├── class-pillen-public.php         # Frontend
│   ├── js/pillen-app.js                # Frontend JavaScript
│   └── css/
│       ├── pillen.css                  # Haupt-Styles
│       └── foundation.css              # Basis-Styles
├── languages/                          # Übersetzungsdateien
└── assets/                             # Bilder, Icons
```

## Anforderungen

- WordPress 5.0 oder höher
- PHP 7.2 oder höher

## Lizenz

GNU General Public License v3.0

## Credits

- Original entwickelt von [Édouard](https://twitter.com/vied12)
- Idee von [Anne-Lise](https://twitter.com/annelisebouyer)
- Mit Unterstützung von [Sebastian](https://twitter.com/sm_kraus) und [Olivier](https://twitter.com/olivier_chardin)
- WordPress Plugin Portierung: Claude AI

## Support

Für Fragen und Support besuchen Sie: http://www.mindzone.info
