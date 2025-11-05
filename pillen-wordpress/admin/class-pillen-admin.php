<?php
/**
 * Admin functionality for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Admin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_post_pillen_import_json', array($this, 'handle_json_import'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=pille',
            __('Import', 'pillen'),
            __('Import', 'pillen'),
            'manage_options',
            'pillen-import',
            array($this, 'render_import_page')
        );

        add_submenu_page(
            'edit.php?post_type=pille',
            __('Einstellungen', 'pillen'),
            __('Einstellungen', 'pillen'),
            'manage_options',
            'pillen-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'pille') !== false) {
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }
    }

    /**
     * Render import page
     */
    public function render_import_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Pillen importieren', 'pillen'); ?></h1>

            <?php
            if (isset($_GET['import_result'])) {
                $result = get_transient('pillen_import_result');
                if ($result) {
                    delete_transient('pillen_import_result');
                    ?>
                    <div class="notice notice-<?php echo $result['errors'] > 0 ? 'warning' : 'success'; ?> is-dismissible">
                        <p>
                            <strong><?php echo sprintf(__('%d Pillen erfolgreich importiert.', 'pillen'), $result['success']); ?></strong>
                            <?php if ($result['errors'] > 0): ?>
                                <br><?php echo sprintf(__('%d Fehler aufgetreten.', 'pillen'), $result['errors']); ?>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($result['messages'])): ?>
                            <ul style="list-style: disc; margin-left: 20px;">
                                <?php foreach (array_slice($result['messages'], 0, 10) as $message): ?>
                                    <li><?php echo esc_html($message); ?></li>
                                <?php endforeach; ?>
                                <?php if (count($result['messages']) > 10): ?>
                                    <li><em><?php echo sprintf(__('... und %d weitere Nachrichten', 'pillen'), count($result['messages']) - 10); ?></em></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }
            ?>

            <div class="card">
                <h2><?php _e('JSON-Datei importieren', 'pillen'); ?></h2>
                <p><?php _e('Importieren Sie Pillen-Daten aus einer JSON-Datei.', 'pillen'); ?></p>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                    <?php wp_nonce_field('pillen_import_json', 'pillen_import_nonce'); ?>
                    <input type="hidden" name="action" value="pillen_import_json">

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="json_file"><?php _e('JSON-Datei', 'pillen'); ?></label>
                            </th>
                            <td>
                                <input type="file" name="json_file" id="json_file" accept=".json" required>
                                <p class="description">
                                    <?php _e('Wählen Sie eine JSON-Datei mit Pillen-Daten zum Importieren aus.', 'pillen'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(__('Jetzt importieren', 'pillen')); ?>
                </form>
            </div>

            <div class="card" style="margin-top: 20px;">
                <h2><?php _e('Schnellimport der bestehenden Daten', 'pillen'); ?></h2>
                <p><?php _e('Importieren Sie die Pillen aus der bestehenden static/pillen.json Datei.', 'pillen'); ?></p>

                <?php
                $default_json = PILLEN_PLUGIN_DIR . '../static/pillen.json';
                if (file_exists($default_json)):
                ?>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <?php wp_nonce_field('pillen_import_json', 'pillen_import_nonce'); ?>
                        <input type="hidden" name="action" value="pillen_import_json">
                        <input type="hidden" name="use_default" value="1">
                        <?php submit_button(__('Bestehende Daten importieren', 'pillen'), 'secondary'); ?>
                    </form>
                <?php else: ?>
                    <p class="description" style="color: #d63638;">
                        <?php _e('Die Datei static/pillen.json wurde nicht gefunden.', 'pillen'); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Handle JSON import
     */
    public function handle_json_import() {
        // Check nonce
        if (!isset($_POST['pillen_import_nonce']) ||
            !wp_verify_nonce($_POST['pillen_import_nonce'], 'pillen_import_json')) {
            wp_die(__('Sicherheitsüberprüfung fehlgeschlagen.', 'pillen'));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('Keine Berechtigung.', 'pillen'));
        }

        $json_file = '';

        // Check if using default file
        if (isset($_POST['use_default']) && $_POST['use_default'] == '1') {
            $json_file = PILLEN_PLUGIN_DIR . '../static/pillen.json';
        } else {
            // Handle uploaded file
            if (!isset($_FILES['json_file']) || $_FILES['json_file']['error'] !== UPLOAD_ERR_OK) {
                wp_die(__('Fehler beim Hochladen der Datei.', 'pillen'));
            }

            $json_file = $_FILES['json_file']['tmp_name'];
        }

        // Import
        $importer = Pillen_Importer::get_instance();
        $result = $importer->import_from_json($json_file);

        // Store result in transient
        set_transient('pillen_import_result', $result, 60);

        // Redirect back
        wp_redirect(add_query_arg(
            array(
                'post_type' => 'pille',
                'page' => 'pillen-import',
                'import_result' => '1'
            ),
            admin_url('edit.php')
        ));
        exit;
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Pillen Einstellungen', 'pillen'); ?></h1>

            <div class="card">
                <h2><?php _e('Shortcode', 'pillen'); ?></h2>
                <p><?php _e('Verwenden Sie den folgenden Shortcode, um die Pillen-Datenbank auf einer Seite anzuzeigen:', 'pillen'); ?></p>
                <code>[pillen_database]</code>
                <br><br>
                <p><?php _e('Für eine eingebettete Version ohne Header/Footer:', 'pillen'); ?></p>
                <code>[pillen_database embedded="true"]</code>
            </div>

            <div class="card" style="margin-top: 20px;">
                <h2><?php _e('Statistik', 'pillen'); ?></h2>
                <?php
                $pill_count = wp_count_posts('pille');
                $published = $pill_count->publish;
                ?>
                <p><?php echo sprintf(__('Gesamt veröffentlichte Pillen: %d', 'pillen'), $published); ?></p>
            </div>
        </div>
        <?php
    }
}
