<?php
/**
 * Mindzone Theme Functions
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function mindzone_setup() {
    // Add theme support
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');

    // Custom image sizes
    add_image_size('pill-thumbnail', 300, 300, true);
    add_image_size('pill-large', 800, 800, false);
    add_image_size('warning-thumbnail', 400, 400, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mindzone'),
        'footer' => __('Footer Menu', 'mindzone'),
        'language-switcher' => __('Language Switcher', 'mindzone'),
    ));

    // Load text domain
    load_theme_textdomain('mindzone', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'mindzone_setup');

// Enqueue scripts and styles
function mindzone_scripts() {
    // Main stylesheet
    wp_enqueue_style('mindzone-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

    // Custom CSS
    wp_enqueue_style('mindzone-custom', get_template_directory_uri() . '/assets/css/custom.css', array('mindzone-style'), '1.0.0');

    // Custom JS
    wp_enqueue_script('mindzone-app', get_template_directory_uri() . '/assets/js/app.js', array('jquery'), '1.0.0', true);

    // Localize script
    wp_localize_script('mindzone-app', 'mindzoneData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mindzone_nonce'),
        'language' => defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'de',
        'translations' => array(
            'loadMore' => __('Load More', 'mindzone'),
            'loading' => __('Loading...', 'mindzone'),
            'noResults' => __('No results found', 'mindzone'),
        )
    ));
}
add_action('wp_enqueue_scripts', 'mindzone_scripts');

// Register Custom Post Types
require_once get_template_directory() . '/inc/custom-post-types.php';

// Register Taxonomies
require_once get_template_directory() . '/inc/taxonomies.php';

// WPML Configuration
require_once get_template_directory() . '/inc/wpml-config.php';

// Custom Functions
require_once get_template_directory() . '/inc/helpers.php';

// DSGVO Compliance
require_once get_template_directory() . '/inc/dsgvo.php';

// Block Patterns
require_once get_template_directory() . '/inc/block-patterns.php';

/**
 * Add WPML language switcher to navigation
 */
function mindzone_language_switcher() {
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');

        if (!empty($languages)) {
            echo '<div class="language-switcher">';
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

                $active_class = $lang['active'] ? 'active' : '';
                echo sprintf(
                    '<a href="%s" class="lang-link %s" title="%s">%s %s</a>',
                    esc_url($lang['url']),
                    $active_class,
                    esc_attr($lang['native_name']),
                    $flag,
                    esc_html($lang['code'])
                );
            }
            echo '</div>';
        }
    }
}

/**
 * Custom excerpt length
 */
function mindzone_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'mindzone_excerpt_length');

/**
 * Custom excerpt more
 */
function mindzone_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'mindzone_excerpt_more');

/**
 * Add custom body classes
 */
function mindzone_body_classes($classes) {
    // Add language class
    if (defined('ICL_LANGUAGE_CODE')) {
        $classes[] = 'lang-' . ICL_LANGUAGE_CODE;
    }

    // Add custom post type class
    if (is_singular()) {
        $classes[] = 'single-' . get_post_type();
    }

    return $classes;
}
add_filter('body_class', 'mindzone_body_classes');

/**
 * Disable Gutenberg for specific post types (optional)
 */
function mindzone_disable_gutenberg($use_block_editor, $post_type) {
    // Keep Gutenberg for pages and posts
    // Disable for custom post types if needed
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'mindzone_disable_gutenberg', 10, 2);

/**
 * Add admin notice for WPML
 */
function mindzone_wpml_notice() {
    if (!defined('ICL_SITEPRESS_VERSION')) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('Mindzone Theme: WPML Plugin is recommended for multilingual support (DE/EN/TR).', 'mindzone'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'mindzone_wpml_notice');
