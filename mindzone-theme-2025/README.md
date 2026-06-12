# Mindzone FSE Theme 2025

Modernes Full Site Editing (FSE) Theme für mindzone.info - Harm Reduction & Drogenprävention

## Features

### 🌍 Mehrsprachigkeit (WPML)
- 🇩🇪 Deutsch (Hauptsprache)
- 🇬🇧 Englisch (International)
- 🇹🇷 Türkisch (Migrantengruppe Bayern)

### 📝 Custom Post Types
1. **Substanzwarnungen** - Drug Warnings
   - Auto-Import via Scraper (Saferparty, CheckIt)
   - Warnstufen (Hoch/Mittel/Niedrig)
   - Quellen-Attribution

2. **Substanzen** - Substance Information
   - Detaillierte Infos zu psychoaktiven Substanzen
   - Taxonomies: Substanzklasse, Wirkstoff

3. **Pillen** - Pill Database
   - Integration mit pillen-wordpress Plugin
   - 7.500+ Einträge

4. **Einsätze** - Peer Events
   - Festivals, Clubs, Schulungen
   - Datum, Ort, Team

5. **Podcasts** - sauberdrauf! Podcast
   - Audio-Player
   - RSS Feed

6. **Videos** - Dr. Schepper Video-Beratung
   - YouTube Privacy-Mode Embeds
   - Untertitel-Support

### 🔒 DSGVO-Konformität
- ✅ Keine Google Fonts (System Fonts)
- ✅ YouTube Privacy-Mode (youtube-nocookie.com)
- ✅ Keine Tracking-Tools von Drittanbietern
- ✅ Cookie-Notice (einfach, ohne externe Services)
- ✅ IP-Anonymisierung
- ✅ Lokale Assets

### 🎨 Design
- Modern & Clean
- Responsive (Mobile-First)
- Accessibility (WCAG 2.1 AA)
- Performance-Optimiert
- Block Patterns

### ⚡ Performance
- Lazy Loading für Bilder/Videos
- Minified Assets
- Keine jQuery Migrate
- Optimierte Fonts
- Caching-Ready

## Installation

1. **Theme hochladen:**
   ```bash
   wp-content/themes/mindzone-theme-2025/
   ```

2. **Theme aktivieren:**
   WordPress Admin → Design → Themes → Mindzone 2025 aktivieren

3. **WPML konfigurieren:**
   - Sprachen hinzufügen: DE (default), EN, TR
   - String Translation aktivieren
   - Auto-Translate konfigurieren (optional)

4. **Permalinks erneuern:**
   Einstellungen → Permalinks → Speichern

## Verwendung

### Navigation erstellen
Design → Navigation → Neue Navigation:
- Primary Menu (Hauptmenü)
- Footer Menu (Footer-Links)

### Sprach-Switcher
Wird automatisch im Header angezeigt (WPML)

### Custom Post Types
Nach Aktivierung verfügbar:
- Substanzwarnungen
- Substanzen
- Einsätze
- Podcasts
- Videos

### Block Patterns
Design → Muster → Mindzone

## Struktur

```
mindzone-theme-2025/
├── style.css               # Theme Header
├── theme.json              # FSE Config (Farben, Fonts, etc.)
├── functions.php           # Theme Functions
├── templates/              # Seiten-Templates
│   └── index.html
├── parts/                  # Template Parts
│   ├── header.html
│   └── footer.html
├── patterns/               # Block Patterns
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
├── inc/
│   ├── custom-post-types.php
│   ├── taxonomies.php
│   ├── wpml-config.php
│   ├── dsgvo.php
│   ├── helpers.php
│   └── block-patterns.php
└── languages/              # Übersetzungen
    ├── de_DE.po
    ├── en_US.po
    └── tr_TR.po
```

## Entwicklung

### Lokale Entwicklung
```bash
# Theme aktivieren
cd wp-content/themes/mindzone-theme-2025

# Watch für CSS/JS (wenn Build-System vorhanden)
npm run watch
```

### Übersetzungen
```bash
# .pot Datei generieren
wp i18n make-pot . languages/mindzone.pot

# .po zu .mo kompilieren
msgfmt languages/de_DE.po -o languages/de_DE.mo
```

## Plugins (Empfohlen)

### Erforderlich:
- **WPML** (Multilingual CMS) - Lifetime Lizenz vorhanden
- **Pillen WordPress Plugin** - Für Pillen-Datenbank

### Empfohlen:
- **Complianz** - Cookie-Banner & DSGVO
- **WP Super Cache** - Performance
- **Yoast SEO** - Suchmaschinen-Optimierung
- **Redirection** - URL-Redirects für Migration

## Farben

```css
--mindzone-orange: #FF6B35   /* Primary */
--mindzone-blue: #004E89     /* Secondary */
--warning-orange: #F77F00    /* Accent */
--success-green: #06A77D     /* Success */
--danger-red: #D62828        /* Danger */
--dark: #1A1A1A              /* Text */
--light: #F8F9FA             /* Background */
```

## Support

Bei Fragen: dev@mindzone.info

## Credits

- Theme-Entwicklung: Claude AI
- Design: mindzone.info Team
- Icons: Dashicons
- Fonts: System Fonts (DSGVO-konform)

## Lizenz

GNU General Public License v3.0

---

**sauberdrauf! mindzone.info** - Harm Reduction seit 1996
