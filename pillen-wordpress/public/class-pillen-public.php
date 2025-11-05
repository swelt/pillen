<?php
/**
 * Public-facing functionality for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Public {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_shortcode('pillen_database', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only load on pages with shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'pillen_database')) {
            // CSS
            wp_enqueue_style(
                'pillen-styles',
                PILLEN_PLUGIN_URL . 'public/css/pillen.css',
                array(),
                PILLEN_VERSION
            );

            // Foundation CSS (from original)
            wp_enqueue_style(
                'foundation',
                PILLEN_PLUGIN_URL . 'public/css/foundation.css',
                array(),
                PILLEN_VERSION
            );

            // JavaScript
            wp_enqueue_script('jquery');

            wp_enqueue_script(
                'pillen-app',
                PILLEN_PLUGIN_URL . 'public/js/pillen-app.js',
                array('jquery'),
                PILLEN_VERSION,
                true
            );

            // Localize script with REST API endpoint
            wp_localize_script('pillen-app', 'pillenData', array(
                'apiUrl' => rest_url('pillen/v1'),
                'nonce' => wp_create_nonce('wp_rest'),
                'i18n' => array(
                    'composition' => __('Inhalt', 'pillen'),
                    'breakline' => __('Bruchrille', 'pillen'),
                    'date' => __('Datum', 'pillen'),
                    'thickness' => __('Dicke', 'pillen'),
                    'diameter' => __('Durchmesser', 'pillen'),
                    'place' => __('Ort', 'pillen'),
                    'search_placeholder' => __('logo: android, mc donalds ...', 'pillen'),
                    'tour_step1' => __('Hallo! Dies ist eine Tour in drei Schritten.', 'pillen') . ' ' .
                                   __('Um Pillen zu filtern, kannst du eine Farbe auswählen.', 'pillen') . ' ' .
                                   __('Schwarz ist besonders. Schwarz bedeutet alle.', 'pillen'),
                    'tour_step2' => __('Du kannst auch nach Symbolnamen suchen.', 'pillen') . ' ' .
                                   __('Aber beachte: Die ausgewählte Farbe filtert weiterhin!', 'pillen'),
                    'tour_step3' => __('Willst du mehr? Klicke auf eine Pille.', 'pillen') . ' ' .
                                   __('Jetzt weißt du alles. Viel Spaß!', 'pillen'),
                )
            ));
        }
    }

    /**
     * Render shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'embedded' => 'false'
        ), $atts);

        ob_start();
        ?>
        <div id="pillen-app" class="pillen-database">
            <?php if ($atts['embedded'] !== 'true'): ?>
            <div class="row pillen-header">
                <div class="large-6 columns">
                    <h1><?php _e('PILLEN', 'pillen'); ?></h1>
                    <p><?php _e('Das kolorimetrische Verzeichnis kleiner Pillen', 'pillen'); ?></p>
                </div>
                <div class="large-6 columns text-right">
                    <a href="http://www.mindzone.info/aktuelles/pillenwarnungen/" target="_blank">
                        <img src="<?php echo PILLEN_PLUGIN_URL; ?>assets/sauberdrauf_mindzone.jpg" alt="Mindzone" style="max-width: 200px;">
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Loading indicator -->
            <div id="pillen-loading" class="pillen-loading" style="text-align: center; padding: 50px;">
                <p><?php _e('Lade Pillen-Datenbank...', 'pillen'); ?></p>
            </div>

            <!-- Navigation & Filters -->
            <div id="pillen-navigation" class="pillen-navigation row" style="display: none;">
                <!-- Color filter -->
                <div class="row">
                    <div class="colors columns large-9">
                        <div class="color all active" data-color="all"></div>
                        <div id="color-filters"></div>
                    </div>
                    <div class="search-logo columns large-3">
                        <input type="text" id="pillen-search" placeholder="<?php _e('logo: android, mc donalds ...', 'pillen'); ?>">
                    </div>
                </div>

                <!-- Pills display -->
                <div id="pillen-grid" class="pillen-grid"></div>
            </div>

            <!-- Modal for pill details -->
            <div id="pillen-modal" class="pillen-modal" style="display: none;">
                <div class="pillen-modal-content">
                    <span class="pillen-modal-close">&times;</span>
                    <div id="pillen-modal-body"></div>
                </div>
            </div>

            <?php if ($atts['embedded'] !== 'true'): ?>
            <div class="row pillen-footer">
                <div class="large-6 columns">
                    <?php _e('Danksagungen:', 'pillen'); ?>
                    <?php _e('Entwickelt von <a href="https://twitter.com/vied12" target="_blank">Édouard</a> aus einer brillanten Idee von <a href="https://twitter.com/annelisebouyer" target="_blank">Anne-Lise</a><br/>und mit der nützlichen Beratung von <a href="https://twitter.com/sm_kraus" target="_blank">Sebastian</a> und <a href="https://twitter.com/olivier_chardin" target="_blank">Olivier</a>.', 'pillen'); ?>
                </div>
                <div class="large-6 columns text-right">
                    <?php _e('Quelle', 'pillen'); ?>:
                    <a href="http://www.mindzone.info/aktuelles/pillenwarnungen" target="_blank">
                        www.mindzone.info/aktuelles/pillenwarnungen/
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
