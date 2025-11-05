<?php
/**
 * Plugin Name: Pillen Database
 * Plugin URI: https://github.com/swelt/pillen
 * Description: Das kolorimetrische Verzeichnis kleiner Pillen - Pillenwarnungen für Harm Reduction
 * Version: 1.0.0
 * Author: Mindzone
 * Author URI: http://www.mindzone.info
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pillen
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PILLEN_VERSION', '1.0.0');
define('PILLEN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PILLEN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PILLEN_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Pillen Plugin Class
 */
class Pillen_Plugin {

    /**
     * Single instance of the class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-post-type.php';
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-taxonomies.php';
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-meta-boxes.php';
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-importer.php';
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-rest-api.php';
        require_once PILLEN_PLUGIN_DIR . 'includes/class-pillen-i18n.php';
        require_once PILLEN_PLUGIN_DIR . 'admin/class-pillen-admin.php';
        require_once PILLEN_PLUGIN_DIR . 'public/class-pillen-public.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Activation/Deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Init action
        add_action('init', array($this, 'init'), 0);

        // Load plugin textdomain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }

    /**
     * Init plugin
     */
    public function init() {
        // Initialize components
        Pillen_Post_Type::get_instance();
        Pillen_Taxonomies::get_instance();
        Pillen_Meta_Boxes::get_instance();
        Pillen_REST_API::get_instance();
        Pillen_I18n::get_instance();

        if (is_admin()) {
            Pillen_Admin::get_instance();
        } else {
            Pillen_Public::get_instance();
        }
    }

    /**
     * Load plugin textdomain for translations
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'pillen',
            false,
            dirname(PILLEN_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Register post type and taxonomies
        Pillen_Post_Type::get_instance()->register_post_type();
        Pillen_Taxonomies::get_instance()->register_taxonomies();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options
        if (!get_option('pillen_version')) {
            add_option('pillen_version', PILLEN_VERSION);
        }
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

/**
 * Initialize the plugin
 */
function pillen_plugin() {
    return Pillen_Plugin::get_instance();
}

// Start the plugin
pillen_plugin();
