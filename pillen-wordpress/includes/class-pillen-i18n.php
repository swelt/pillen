<?php
/**
 * Internationalization (i18n) for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_I18n {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Language detection
        add_filter('locale', array($this, 'set_locale'));
    }

    /**
     * Set locale based on query parameter or browser language
     */
    public function set_locale($locale) {
        // Check for language parameter in URL
        if (isset($_GET['lang'])) {
            $lang = sanitize_text_field($_GET['lang']);
            if (in_array($lang, array('de', 'fr', 'en'))) {
                return $lang . '_' . strtoupper($lang);
            }
        }

        // Use WordPress default
        return $locale;
    }

    /**
     * Get available languages
     */
    public function get_available_languages() {
        return array(
            'de_DE' => __('Deutsch', 'pillen'),
            'fr_FR' => __('Français', 'pillen'),
            'en_US' => __('English', 'pillen')
        );
    }
}
