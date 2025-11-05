<?php
/**
 * Custom Post Type: Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Post_Type {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Pillen', 'Post Type General Name', 'pillen'),
            'singular_name'         => _x('Pille', 'Post Type Singular Name', 'pillen'),
            'menu_name'             => __('Pillen', 'pillen'),
            'name_admin_bar'        => __('Pille', 'pillen'),
            'archives'              => __('Pillen Archiv', 'pillen'),
            'attributes'            => __('Pillen Attribute', 'pillen'),
            'parent_item_colon'     => __('Übergeordnete Pille:', 'pillen'),
            'all_items'             => __('Alle Pillen', 'pillen'),
            'add_new_item'          => __('Neue Pille hinzufügen', 'pillen'),
            'add_new'               => __('Neu hinzufügen', 'pillen'),
            'new_item'              => __('Neue Pille', 'pillen'),
            'edit_item'             => __('Pille bearbeiten', 'pillen'),
            'update_item'           => __('Pille aktualisieren', 'pillen'),
            'view_item'             => __('Pille ansehen', 'pillen'),
            'view_items'            => __('Pillen ansehen', 'pillen'),
            'search_items'          => __('Pillen durchsuchen', 'pillen'),
            'not_found'             => __('Nicht gefunden', 'pillen'),
            'not_found_in_trash'    => __('Nicht im Papierkorb gefunden', 'pillen'),
            'featured_image'        => __('Hauptbild', 'pillen'),
            'set_featured_image'    => __('Hauptbild festlegen', 'pillen'),
            'remove_featured_image' => __('Hauptbild entfernen', 'pillen'),
            'use_featured_image'    => __('Als Hauptbild verwenden', 'pillen'),
            'insert_into_item'      => __('In Pille einfügen', 'pillen'),
            'uploaded_to_this_item' => __('Zu dieser Pille hochgeladen', 'pillen'),
            'items_list'            => __('Pillen Liste', 'pillen'),
            'items_list_navigation' => __('Pillen Listen Navigation', 'pillen'),
            'filter_items_list'     => __('Pillen Liste filtern', 'pillen'),
        );

        $args = array(
            'label'                 => __('Pille', 'pillen'),
            'description'           => __('Pillenwarnungen und Informationen', 'pillen'),
            'labels'                => $labels,
            'supports'              => array('title', 'thumbnail', 'custom-fields'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-warning',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'show_in_rest'          => true,
            'rest_base'             => 'pillen',
            'capability_type'       => 'post',
        );

        register_post_type('pille', $args);
    }
}
