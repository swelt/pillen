<?php
/**
 * JSON Importer for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Importer {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Import pills from JSON file
     *
     * @param string $json_file_path Path to JSON file
     * @return array Results with success/error counts
     */
    public function import_from_json($json_file_path) {
        $results = array(
            'success' => 0,
            'errors' => 0,
            'messages' => array()
        );

        if (!file_exists($json_file_path)) {
            $results['messages'][] = __('JSON-Datei nicht gefunden.', 'pillen');
            $results['errors']++;
            return $results;
        }

        $json_content = file_get_contents($json_file_path);
        $pills = json_decode($json_content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $results['messages'][] = __('Fehler beim Parsen der JSON-Datei: ', 'pillen') . json_last_error_msg();
            $results['errors']++;
            return $results;
        }

        if (!is_array($pills)) {
            $results['messages'][] = __('Ungültiges JSON-Format.', 'pillen');
            $results['errors']++;
            return $results;
        }

        foreach ($pills as $pill_data) {
            $result = $this->import_single_pill($pill_data);
            if ($result['success']) {
                $results['success']++;
            } else {
                $results['errors']++;
                $results['messages'][] = $result['message'];
            }
        }

        return $results;
    }

    /**
     * Import a single pill
     *
     * @param array $data Pill data from JSON
     * @return array Result with success status and message
     */
    private function import_single_pill($data) {
        $result = array('success' => false, 'message' => '');

        // Required field
        if (empty($data['name'])) {
            $result['message'] = __('Pille ohne Namen übersprungen.', 'pillen');
            return $result;
        }

        // Check if pill already exists
        $existing = get_posts(array(
            'post_type' => 'pille',
            'title' => $data['name'],
            'meta_query' => array(
                array(
                    'key' => '_pillen_datum',
                    'value' => isset($data['datum']) ? $this->convert_date($data['datum']) : '',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));

        if (!empty($existing)) {
            $result['message'] = sprintf(__('Pille "%s" existiert bereits.', 'pillen'), $data['name']);
            return $result;
        }

        // Create post
        $post_id = wp_insert_post(array(
            'post_title' => sanitize_text_field($data['name']),
            'post_type' => 'pille',
            'post_status' => 'publish',
            'post_content' => ''
        ));

        if (is_wp_error($post_id)) {
            $result['message'] = sprintf(__('Fehler beim Erstellen der Pille "%s": %s', 'pillen'), $data['name'], $post_id->get_error_message());
            return $result;
        }

        // Set taxonomies
        if (!empty($data['farbe'])) {
            wp_set_object_terms($post_id, $data['farbe'], 'pillen_farbe');
        }
        if (!empty($data['ort'])) {
            wp_set_object_terms($post_id, $data['ort'], 'pillen_ort');
        }
        if (!empty($data['quelle'])) {
            wp_set_object_terms($post_id, $data['quelle'], 'pillen_quelle');
        }
        if (!empty($data['logo'])) {
            wp_set_object_terms($post_id, $data['logo'], 'pillen_logo');
        }

        // Set meta fields
        if (isset($data['datum'])) {
            update_post_meta($post_id, '_pillen_datum', $this->convert_date($data['datum']));
        }
        if (isset($data['durchmesser'])) {
            update_post_meta($post_id, '_pillen_durchmesser', sanitize_text_field($data['durchmesser']));
        }
        if (isset($data['dicke'])) {
            update_post_meta($post_id, '_pillen_dicke', sanitize_text_field($data['dicke']));
        }
        if (isset($data['gewicht'])) {
            update_post_meta($post_id, '_pillen_gewicht', sanitize_text_field($data['gewicht']));
        }
        if (isset($data['bruchrille'])) {
            update_post_meta($post_id, '_pillen_bruchrille', sanitize_text_field($data['bruchrille']));
        }
        if (isset($data['inhalt'])) {
            update_post_meta($post_id, '_pillen_inhalt', sanitize_text_field($data['inhalt']));
        }

        // Set composition (array of substances)
        if (isset($data['composition']) && is_array($data['composition'])) {
            update_post_meta($post_id, '_pillen_composition', $data['composition']);
        }

        // Set colorz (dominant colors)
        if (isset($data['colorz']) && is_array($data['colorz'])) {
            update_post_meta($post_id, '_pillen_colorz', $data['colorz']);
        }

        // Handle images
        if (isset($data['images']) && is_array($data['images'])) {
            $this->import_pill_images($post_id, $data['images']);
        }

        $result['success'] = true;
        $result['message'] = sprintf(__('Pille "%s" erfolgreich importiert.', 'pillen'), $data['name']);
        return $result;
    }

    /**
     * Import images for a pill
     *
     * @param int $post_id Post ID
     * @param array $image_filenames Array of image filenames
     */
    private function import_pill_images($post_id, $image_filenames) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_ids = array();
        $upload_dir = wp_upload_dir();

        // Path to original images (adjust based on your setup)
        $source_dir = PILLEN_PLUGIN_DIR . '../static/pillen/';

        foreach ($image_filenames as $index => $filename) {
            $source_file = $source_dir . $filename;

            if (!file_exists($source_file)) {
                continue;
            }

            // Copy file to WordPress uploads
            $filename_new = 'pill-' . $post_id . '-' . ($index + 1) . '-' . basename($filename);
            $target_file = $upload_dir['path'] . '/' . $filename_new;

            if (copy($source_file, $target_file)) {
                // Create attachment
                $filetype = wp_check_filetype($target_file);
                $attachment = array(
                    'guid' => $upload_dir['url'] . '/' . $filename_new,
                    'post_mime_type' => $filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename_new)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment, $target_file, $post_id);

                if (!is_wp_error($attach_id)) {
                    $attach_data = wp_generate_attachment_metadata($attach_id, $target_file);
                    wp_update_attachment_metadata($attach_id, $attach_data);
                    $image_ids[] = $attach_id;

                    // Set first image as featured image
                    if ($index === 0) {
                        set_post_thumbnail($post_id, $attach_id);
                    }
                }
            }
        }

        if (!empty($image_ids)) {
            update_post_meta($post_id, '_pillen_image_ids', $image_ids);
        }
    }

    /**
     * Convert date from German format to YYYY-MM-DD
     *
     * @param string $date_str Date string in format like "26.09.2014"
     * @return string Date in YYYY-MM-DD format
     */
    private function convert_date($date_str) {
        // Try to parse German date format (DD.MM.YYYY)
        if (preg_match('/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/', $date_str, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        return $date_str;
    }
}
