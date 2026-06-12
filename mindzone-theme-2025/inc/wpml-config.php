<?php
/**
 * WPML Configuration
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configure WPML for Mindzone Theme
 */
function mindzone_wpml_config() {
    if (!defined('ICL_SITEPRESS_VERSION')) {
        return;
    }

    // Default languages: DE, EN, TR
    $default_languages = array(
        'de' => array(
            'code' => 'de',
            'default_locale' => 'de_DE',
            'label' => 'Deutsch',
            'flag' => '🇩🇪',
        ),
        'en' => array(
            'code' => 'en',
            'default_locale' => 'en_US',
            'label' => 'English',
            'flag' => '🇬🇧',
        ),
        'tr' => array(
            'code' => 'tr',
            'default_locale' => 'tr_TR',
            'label' => 'Türkçe',
            'flag' => '🇹🇷',
        ),
    );

    // Register custom post types and taxonomies with WPML
    do_action('wpml_register_single_string', 'mindzone', 'Site Title', get_bloginfo('name'));
    do_action('wpml_register_single_string', 'mindzone', 'Site Description', get_bloginfo('description'));
}
add_action('init', 'mindzone_wpml_config');

/**
 * Add WPML language info to body class
 */
function mindzone_wpml_body_class($classes) {
    if (defined('ICL_LANGUAGE_CODE')) {
        $classes[] = 'wpml-lang-' . ICL_LANGUAGE_CODE;
    }
    return $classes;
}
add_filter('body_class', 'mindzone_wpml_body_class');

/**
 * Custom WPML language switcher
 */
function mindzone_wpml_language_switcher($args = array()) {
    if (!function_exists('icl_get_languages')) {
        return '';
    }

    $defaults = array(
        'skip_missing' => 0,
        'orderby' => 'custom',
        'order' => 'asc',
    );

    $args = wp_parse_args($args, $defaults);
    $languages = icl_get_languages('skip_missing=' . $args['skip_missing'] . '&orderby=' . $args['orderby']);

    if (empty($languages)) {
        return '';
    }

    $output = '<div class="wpml-language-switcher">';

    foreach ($languages as $lang) {
        $flag = '';
        switch ($lang['language_code']) {
            case 'de':
                $flag = '🇩🇪';
                break;
            case 'en':
                $flag = '🇬🇧';
                break;
            case 'tr':
                $flag = '🇹🇷';
                break;
        }

        $active_class = $lang['active'] ? ' active' : '';

        $output .= sprintf(
            '<a href="%s" class="lang-link%s" hreflang="%s" title="%s">
                <span class="lang-flag">%s</span>
                <span class="lang-code">%s</span>
            </a>',
            esc_url($lang['url']),
            $active_class,
            esc_attr($lang['language_code']),
            esc_attr($lang['native_name']),
            $flag,
            strtoupper(esc_html($lang['code']))
        );
    }

    $output .= '</div>';

    return $output;
}

/**
 * Get current language
 */
function mindzone_get_current_language() {
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    return 'de'; // Default to German
}

/**
 * Get translated post/page ID
 */
function mindzone_get_translated_id($post_id, $lang_code = null) {
    if (!function_exists('icl_object_id')) {
        return $post_id;
    }

    if (null === $lang_code) {
        $lang_code = mindzone_get_current_language();
    }

    return icl_object_id($post_id, get_post_type($post_id), true, $lang_code);
}

/**
 * Check if post is translated
 */
function mindzone_is_translated($post_id, $lang_code) {
    if (!function_exists('icl_get_languages')) {
        return false;
    }

    $translated_id = mindzone_get_translated_id($post_id, $lang_code);
    return $translated_id && $translated_id !== $post_id;
}

/**
 * Get language name
 */
function mindzone_get_language_name($lang_code = null) {
    if (null === $lang_code) {
        $lang_code = mindzone_get_current_language();
    }

    $names = array(
        'de' => __('Deutsch', 'mindzone'),
        'en' => __('English', 'mindzone'),
        'tr' => __('Türkçe', 'mindzone'),
    );

    return isset($names[$lang_code]) ? $names[$lang_code] : $lang_code;
}

/**
 * Output language switcher
 */
function mindzone_language_switcher_output() {
    echo mindzone_wpml_language_switcher();
}
