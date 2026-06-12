<?php
/**
 * Custom Post Types for Mindzone
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Types
 */
function mindzone_register_post_types() {

    // 1. SUBSTANZWARNUNGEN (Substance Warnings)
    register_post_type('substanzwarnung', array(
        'labels' => array(
            'name' => __('Substanzwarnungen', 'mindzone'),
            'singular_name' => __('Substanzwarnung', 'mindzone'),
            'add_new' => __('Neue Warnung', 'mindzone'),
            'add_new_item' => __('Neue Substanzwarnung hinzufügen', 'mindzone'),
            'edit_item' => __('Warnung bearbeiten', 'mindzone'),
            'new_item' => __('Neue Warnung', 'mindzone'),
            'view_item' => __('Warnung ansehen', 'mindzone'),
            'search_items' => __('Warnungen durchsuchen', 'mindzone'),
            'not_found' => __('Keine Warnungen gefunden', 'mindzone'),
            'not_found_in_trash' => __('Keine Warnungen im Papierkorb', 'mindzone'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-warning',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'warnungen'),
        'capability_type' => 'post',
        'show_in_menu' => true,
        'menu_position' => 5,
    ));

    // 2. SUBSTANZEN (Substances)
    register_post_type('substanz', array(
        'labels' => array(
            'name' => __('Substanzen', 'mindzone'),
            'singular_name' => __('Substanz', 'mindzone'),
            'add_new' => __('Neue Substanz', 'mindzone'),
            'add_new_item' => __('Neue Substanz hinzufügen', 'mindzone'),
            'edit_item' => __('Substanz bearbeiten', 'mindzone'),
            'new_item' => __('Neue Substanz', 'mindzone'),
            'view_item' => __('Substanz ansehen', 'mindzone'),
            'search_items' => __('Substanzen durchsuchen', 'mindzone'),
            'not_found' => __('Keine Substanzen gefunden', 'mindzone'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-chemistry',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
        'rewrite' => array('slug' => 'substanzen'),
        'capability_type' => 'post',
        'show_in_menu' => true,
        'menu_position' => 6,
    ));

    // 3. EVENTS/EINSÄTZE (Peer Events)
    register_post_type('einsatz', array(
        'labels' => array(
            'name' => __('Einsätze', 'mindzone'),
            'singular_name' => __('Einsatz', 'mindzone'),
            'add_new' => __('Neuer Einsatz', 'mindzone'),
            'add_new_item' => __('Neuen Einsatz hinzufügen', 'mindzone'),
            'edit_item' => __('Einsatz bearbeiten', 'mindzone'),
            'new_item' => __('Neuer Einsatz', 'mindzone'),
            'view_item' => __('Einsatz ansehen', 'mindzone'),
            'search_items' => __('Einsätze durchsuchen', 'mindzone'),
            'not_found' => __('Keine Einsätze gefunden', 'mindzone'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'einsaetze'),
        'capability_type' => 'post',
        'show_in_menu' => true,
        'menu_position' => 7,
    ));

    // 4. PODCASTS
    register_post_type('podcast', array(
        'labels' => array(
            'name' => __('Podcasts', 'mindzone'),
            'singular_name' => __('Podcast', 'mindzone'),
            'add_new' => __('Neue Episode', 'mindzone'),
            'add_new_item' => __('Neue Podcast-Episode hinzufügen', 'mindzone'),
            'edit_item' => __('Episode bearbeiten', 'mindzone'),
            'new_item' => __('Neue Episode', 'mindzone'),
            'view_item' => __('Episode ansehen', 'mindzone'),
            'search_items' => __('Episoden durchsuchen', 'mindzone'),
            'not_found' => __('Keine Episoden gefunden', 'mindzone'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-microphone',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'podcast'),
        'capability_type' => 'post',
        'show_in_menu' => true,
        'menu_position' => 8,
    ));

    // 5. VIDEOS (Dr. Schepper)
    register_post_type('video', array(
        'labels' => array(
            'name' => __('Videos', 'mindzone'),
            'singular_name' => __('Video', 'mindzone'),
            'add_new' => __('Neues Video', 'mindzone'),
            'add_new_item' => __('Neues Video hinzufügen', 'mindzone'),
            'edit_item' => __('Video bearbeiten', 'mindzone'),
            'new_item' => __('Neues Video', 'mindzone'),
            'view_item' => __('Video ansehen', 'mindzone'),
            'search_items' => __('Videos durchsuchen', 'mindzone'),
            'not_found' => __('Keine Videos gefunden', 'mindzone'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-video-alt3',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'videos'),
        'capability_type' => 'post',
        'show_in_menu' => true,
        'menu_position' => 9,
    ));
}
add_action('init', 'mindzone_register_post_types');

/**
 * Flush rewrite rules on theme activation
 */
function mindzone_rewrite_flush() {
    mindzone_register_post_types();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mindzone_rewrite_flush');
