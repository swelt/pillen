<?php
/**
 * Custom Taxonomies for Mindzone
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Taxonomies
 */
function mindzone_register_taxonomies() {

    // 1. SUBSTANZKLASSE (Substance Class)
    register_taxonomy('substanzklasse', array('substanz', 'substanzwarnung'), array(
        'labels' => array(
            'name' => __('Substanzklassen', 'mindzone'),
            'singular_name' => __('Substanzklasse', 'mindzone'),
            'search_items' => __('Klassen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Klassen', 'mindzone'),
            'edit_item' => __('Klasse bearbeiten', 'mindzone'),
            'update_item' => __('Klasse aktualisieren', 'mindzone'),
            'add_new_item' => __('Neue Klasse hinzufügen', 'mindzone'),
            'new_item_name' => __('Neuer Klassenname', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'substanzklasse'),
    ));

    // 2. WARNSTUFE (Warning Level)
    register_taxonomy('warnstufe', 'substanzwarnung', array(
        'labels' => array(
            'name' => __('Warnstufen', 'mindzone'),
            'singular_name' => __('Warnstufe', 'mindzone'),
            'search_items' => __('Warnstufen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Warnstufen', 'mindzone'),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'warnstufe'),
    ));

    // 3. QUELLE (Source - Saferparty, CheckIt, etc.)
    register_taxonomy('quelle', 'substanzwarnung', array(
        'labels' => array(
            'name' => __('Quellen', 'mindzone'),
            'singular_name' => __('Quelle', 'mindzone'),
            'search_items' => __('Quellen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Quellen', 'mindzone'),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'quelle'),
    ));

    // 4. REGION/ORT (Location)
    register_taxonomy('region', array('substanzwarnung', 'einsatz', 'standort'), array(
        'labels' => array(
            'name' => __('Regionen', 'mindzone'),
            'singular_name' => __('Region', 'mindzone'),
            'search_items' => __('Regionen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Regionen', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'region'),
    ));

    // 5. EVENT-KATEGORIE (Event Category)
    register_taxonomy('event_kategorie', 'einsatz', array(
        'labels' => array(
            'name' => __('Event-Kategorien', 'mindzone'),
            'singular_name' => __('Event-Kategorie', 'mindzone'),
            'search_items' => __('Kategorien durchsuchen', 'mindzone'),
            'all_items' => __('Alle Kategorien', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'event-kategorie'),
    ));

    // 6. PODCAST-KATEGORIE
    register_taxonomy('podcast_kategorie', 'podcast', array(
        'labels' => array(
            'name' => __('Podcast-Kategorien', 'mindzone'),
            'singular_name' => __('Kategorie', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'podcast-kategorie'),
    ));

    // 7. VIDEO-KATEGORIE (Dr. Schepper Topics)
    register_taxonomy('video_kategorie', 'video', array(
        'labels' => array(
            'name' => __('Video-Kategorien', 'mindzone'),
            'singular_name' => __('Kategorie', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'video-kategorie'),
    ));

    // 8. PROJEKT-KATEGORIE (Project/Campaign Type)
    register_taxonomy('projekt_kategorie', 'projekt', array(
        'labels' => array(
            'name' => __('Projekt-Kategorien', 'mindzone'),
            'singular_name' => __('Projekt-Kategorie', 'mindzone'),
            'search_items' => __('Kategorien durchsuchen', 'mindzone'),
            'all_items' => __('Alle Kategorien', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'projekt-kategorie'),
    ));

    // 9. INFOMATERIAL-TYP (Material Type - Flyer, Poster, Broschüre, etc.)
    register_taxonomy('material_typ', 'infomaterial', array(
        'labels' => array(
            'name' => __('Material-Typen', 'mindzone'),
            'singular_name' => __('Material-Typ', 'mindzone'),
            'search_items' => __('Typen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Typen', 'mindzone'),
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'material-typ'),
    ));

    // 10. ZIELGRUPPE (Target Audience)
    register_taxonomy('zielgruppe', array('infomaterial', 'projekt'), array(
        'labels' => array(
            'name' => __('Zielgruppen', 'mindzone'),
            'singular_name' => __('Zielgruppe', 'mindzone'),
            'search_items' => __('Zielgruppen durchsuchen', 'mindzone'),
            'all_items' => __('Alle Zielgruppen', 'mindzone'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'zielgruppe'),
    ));
}
add_action('init', 'mindzone_register_taxonomies');

/**
 * Add default terms on theme activation
 */
function mindzone_add_default_terms() {
    // Warnstufen
    $warnstufen = array('Hoch', 'Mittel', 'Niedrig');
    foreach ($warnstufen as $stufe) {
        if (!term_exists($stufe, 'warnstufe')) {
            wp_insert_term($stufe, 'warnstufe');
        }
    }

    // Quellen
    $quellen = array('Saferparty', 'CheckIt! Wien', 'Eigenanalyse');
    foreach ($quellen as $quelle) {
        if (!term_exists($quelle, 'quelle')) {
            wp_insert_term($quelle, 'quelle');
        }
    }

    // Event-Kategorien
    $event_cats = array('Festival', 'Club', 'Openair', 'Schulung', 'Beratung');
    foreach ($event_cats as $cat) {
        if (!term_exists($cat, 'event_kategorie')) {
            wp_insert_term($cat, 'event_kategorie');
        }
    }

    // Substanzklassen
    $substanzklassen = array(
        'Stimulanzien',
        'Psychedelika',
        'Opioide',
        'Dissoziativa',
        'Cannabinoide',
        'Synthetische Cannabinoide',
        'Depressiva',
    );
    foreach ($substanzklassen as $klasse) {
        if (!term_exists($klasse, 'substanzklasse')) {
            wp_insert_term($klasse, 'substanzklasse');
        }
    }

    // Projekt-Kategorien
    $projekt_cats = array(
        'Kampagne',
        'Poster',
        'Schulungsmaterial',
        'Aufklärung',
        'Inklusion',
    );
    foreach ($projekt_cats as $cat) {
        if (!term_exists($cat, 'projekt_kategorie')) {
            wp_insert_term($cat, 'projekt_kategorie');
        }
    }

    // Material-Typen
    $material_typen = array(
        'Flyer',
        'Poster',
        'Broschüre',
        'Infokarte',
        'Sticker',
        'Handout',
    );
    foreach ($material_typen as $typ) {
        if (!term_exists($typ, 'material_typ')) {
            wp_insert_term($typ, 'material_typ');
        }
    }

    // Zielgruppen
    $zielgruppen = array(
        'Feiernde',
        'Pädagog:innen',
        'Eltern',
        'Multiplikator:innen',
        'Peers',
    );
    foreach ($zielgruppen as $gruppe) {
        if (!term_exists($gruppe, 'zielgruppe')) {
            wp_insert_term($gruppe, 'zielgruppe');
        }
    }

    // Regionen (Bayern)
    $regionen = array(
        'München',
        'Nürnberg',
        'Augsburg',
        'Regensburg',
        'Würzburg',
        'Ingolstadt',
    );
    foreach ($regionen as $region) {
        if (!term_exists($region, 'region')) {
            wp_insert_term($region, 'region');
        }
    }
}
add_action('after_switch_theme', 'mindzone_add_default_terms');
