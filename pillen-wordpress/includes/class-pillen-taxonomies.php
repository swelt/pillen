<?php
/**
 * Custom Taxonomies for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Taxonomies {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_taxonomies'));
    }

    /**
     * Register Custom Taxonomies
     */
    public function register_taxonomies() {
        $this->register_color_taxonomy();
        $this->register_location_taxonomy();
        $this->register_source_taxonomy();
        $this->register_logo_taxonomy();
    }

    /**
     * Register Color Taxonomy
     */
    private function register_color_taxonomy() {
        $labels = array(
            'name'              => _x('Farben', 'taxonomy general name', 'pillen'),
            'singular_name'     => _x('Farbe', 'taxonomy singular name', 'pillen'),
            'search_items'      => __('Farben durchsuchen', 'pillen'),
            'all_items'         => __('Alle Farben', 'pillen'),
            'parent_item'       => __('Übergeordnete Farbe', 'pillen'),
            'parent_item_colon' => __('Übergeordnete Farbe:', 'pillen'),
            'edit_item'         => __('Farbe bearbeiten', 'pillen'),
            'update_item'       => __('Farbe aktualisieren', 'pillen'),
            'add_new_item'      => __('Neue Farbe hinzufügen', 'pillen'),
            'new_item_name'     => __('Neuer Farbenname', 'pillen'),
            'menu_name'         => __('Farben', 'pillen'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'farbe'),
        );

        register_taxonomy('pillen_farbe', array('pille'), $args);
    }

    /**
     * Register Location Taxonomy
     */
    private function register_location_taxonomy() {
        $labels = array(
            'name'              => _x('Orte', 'taxonomy general name', 'pillen'),
            'singular_name'     => _x('Ort', 'taxonomy singular name', 'pillen'),
            'search_items'      => __('Orte durchsuchen', 'pillen'),
            'all_items'         => __('Alle Orte', 'pillen'),
            'parent_item'       => __('Übergeordneter Ort', 'pillen'),
            'parent_item_colon' => __('Übergeordneter Ort:', 'pillen'),
            'edit_item'         => __('Ort bearbeiten', 'pillen'),
            'update_item'       => __('Ort aktualisieren', 'pillen'),
            'add_new_item'      => __('Neuen Ort hinzufügen', 'pillen'),
            'new_item_name'     => __('Neuer Ortsname', 'pillen'),
            'menu_name'         => __('Orte', 'pillen'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'ort'),
        );

        register_taxonomy('pillen_ort', array('pille'), $args);
    }

    /**
     * Register Source Taxonomy (Quelle)
     */
    private function register_source_taxonomy() {
        $labels = array(
            'name'              => _x('Quellen', 'taxonomy general name', 'pillen'),
            'singular_name'     => _x('Quelle', 'taxonomy singular name', 'pillen'),
            'search_items'      => __('Quellen durchsuchen', 'pillen'),
            'all_items'         => __('Alle Quellen', 'pillen'),
            'edit_item'         => __('Quelle bearbeiten', 'pillen'),
            'update_item'       => __('Quelle aktualisieren', 'pillen'),
            'add_new_item'      => __('Neue Quelle hinzufügen', 'pillen'),
            'new_item_name'     => __('Neuer Quellenname', 'pillen'),
            'menu_name'         => __('Quellen', 'pillen'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'quelle'),
        );

        register_taxonomy('pillen_quelle', array('pille'), $args);
    }

    /**
     * Register Logo Taxonomy
     */
    private function register_logo_taxonomy() {
        $labels = array(
            'name'              => _x('Logos', 'taxonomy general name', 'pillen'),
            'singular_name'     => _x('Logo', 'taxonomy singular name', 'pillen'),
            'search_items'      => __('Logos durchsuchen', 'pillen'),
            'all_items'         => __('Alle Logos', 'pillen'),
            'edit_item'         => __('Logo bearbeiten', 'pillen'),
            'update_item'       => __('Logo aktualisieren', 'pillen'),
            'add_new_item'      => __('Neues Logo hinzufügen', 'pillen'),
            'new_item_name'     => __('Neuer Logoname', 'pillen'),
            'menu_name'         => __('Logos', 'pillen'),
        );

        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true,
            'rewrite'           => array('slug' => 'logo'),
        );

        register_taxonomy('pillen_logo', array('pille'), $args);
    }
}
