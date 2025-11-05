<?php
/**
 * REST API for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_REST_API {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('pillen/v1', '/pills', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_pills'),
            'permission_callback' => '__return_true'
        ));

        register_rest_route('pillen/v1', '/filters', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_filters'),
            'permission_callback' => '__return_true'
        ));
    }

    /**
     * Get pills with optional filtering
     */
    public function get_pills($request) {
        $params = $request->get_params();

        $args = array(
            'post_type' => 'pille',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );

        // Tax query
        $tax_query = array('relation' => 'AND');

        if (!empty($params['farbe'])) {
            $tax_query[] = array(
                'taxonomy' => 'pillen_farbe',
                'field' => 'slug',
                'terms' => sanitize_text_field($params['farbe'])
            );
        }

        if (!empty($params['ort'])) {
            $tax_query[] = array(
                'taxonomy' => 'pillen_ort',
                'field' => 'slug',
                'terms' => sanitize_text_field($params['ort'])
            );
        }

        if (!empty($params['logo'])) {
            $tax_query[] = array(
                'taxonomy' => 'pillen_logo',
                'field' => 'slug',
                'terms' => sanitize_text_field($params['logo'])
            );
        }

        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }

        // Meta query for search in other fields
        if (!empty($params['search'])) {
            $args['s'] = sanitize_text_field($params['search']);
        }

        $query = new WP_Query($args);
        $pills = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $pills[] = $this->format_pill_data(get_the_ID());
            }
            wp_reset_postdata();
        }

        return rest_ensure_response($pills);
    }

    /**
     * Get available filters
     */
    public function get_filters($request) {
        $filters = array(
            'farben' => array(),
            'orte' => array(),
            'logos' => array(),
            'quellen' => array()
        );

        // Get all terms
        $farben = get_terms(array('taxonomy' => 'pillen_farbe', 'hide_empty' => true));
        foreach ($farben as $term) {
            $filters['farben'][] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'count' => $term->count
            );
        }

        $orte = get_terms(array('taxonomy' => 'pillen_ort', 'hide_empty' => true));
        foreach ($orte as $term) {
            $filters['orte'][] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'count' => $term->count
            );
        }

        $logos = get_terms(array('taxonomy' => 'pillen_logo', 'hide_empty' => true));
        foreach ($logos as $term) {
            $filters['logos'][] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'count' => $term->count
            );
        }

        $quellen = get_terms(array('taxonomy' => 'pillen_quelle', 'hide_empty' => true));
        foreach ($quellen as $term) {
            $filters['quellen'][] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'count' => $term->count
            );
        }

        return rest_ensure_response($filters);
    }

    /**
     * Format pill data for API response
     */
    private function format_pill_data($post_id) {
        $data = array(
            'id' => $post_id,
            'name' => get_the_title($post_id),
            'datum' => get_post_meta($post_id, '_pillen_datum', true),
            'durchmesser' => get_post_meta($post_id, '_pillen_durchmesser', true),
            'dicke' => get_post_meta($post_id, '_pillen_dicke', true),
            'gewicht' => get_post_meta($post_id, '_pillen_gewicht', true),
            'bruchrille' => get_post_meta($post_id, '_pillen_bruchrille', true),
            'inhalt' => get_post_meta($post_id, '_pillen_inhalt', true),
            'composition' => get_post_meta($post_id, '_pillen_composition', true),
            'colorz' => get_post_meta($post_id, '_pillen_colorz', true),
            'images' => array(),
            'farbe' => '',
            'ort' => '',
            'logo' => '',
            'quelle' => ''
        );

        // Get taxonomies
        $farbe_terms = wp_get_post_terms($post_id, 'pillen_farbe');
        if (!empty($farbe_terms) && !is_wp_error($farbe_terms)) {
            $data['farbe'] = $farbe_terms[0]->name;
        }

        $ort_terms = wp_get_post_terms($post_id, 'pillen_ort');
        if (!empty($ort_terms) && !is_wp_error($ort_terms)) {
            $data['ort'] = $ort_terms[0]->name;
        }

        $logo_terms = wp_get_post_terms($post_id, 'pillen_logo');
        if (!empty($logo_terms) && !is_wp_error($logo_terms)) {
            $data['logo'] = $logo_terms[0]->name;
        }

        $quelle_terms = wp_get_post_terms($post_id, 'pillen_quelle');
        if (!empty($quelle_terms) && !is_wp_error($quelle_terms)) {
            $data['quelle'] = $quelle_terms[0]->name;
        }

        // Get images
        $image_ids = get_post_meta($post_id, '_pillen_image_ids', true);
        if (!empty($image_ids) && is_array($image_ids)) {
            foreach ($image_ids as $img_id) {
                $data['images'][] = array(
                    'url' => wp_get_attachment_url($img_id),
                    'thumbnail' => wp_get_attachment_image_url($img_id, 'thumbnail'),
                    'medium' => wp_get_attachment_image_url($img_id, 'medium')
                );
            }
        }

        // Featured image
        if (has_post_thumbnail($post_id)) {
            $thumb_id = get_post_thumbnail_id($post_id);
            array_unshift($data['images'], array(
                'url' => wp_get_attachment_url($thumb_id),
                'thumbnail' => wp_get_attachment_image_url($thumb_id, 'thumbnail'),
                'medium' => wp_get_attachment_image_url($thumb_id, 'medium')
            ));
        }

        return $data;
    }
}
