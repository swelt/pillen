<?php
/**
 * Custom Meta Boxes for Pillen
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pillen_Meta_Boxes {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_pille', array($this, 'save_meta_boxes'));
    }

    /**
     * Add Meta Boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'pillen_details',
            __('Pillen Details', 'pillen'),
            array($this, 'render_details_meta_box'),
            'pille',
            'normal',
            'high'
        );

        add_meta_box(
            'pillen_composition',
            __('Inhalt / Wirkstoffe', 'pillen'),
            array($this, 'render_composition_meta_box'),
            'pille',
            'normal',
            'high'
        );

        add_meta_box(
            'pillen_images',
            __('Zusätzliche Bilder', 'pillen'),
            array($this, 'render_images_meta_box'),
            'pille',
            'side',
            'default'
        );
    }

    /**
     * Render Details Meta Box
     */
    public function render_details_meta_box($post) {
        wp_nonce_field('pillen_meta_box', 'pillen_meta_box_nonce');

        $datum = get_post_meta($post->ID, '_pillen_datum', true);
        $durchmesser = get_post_meta($post->ID, '_pillen_durchmesser', true);
        $dicke = get_post_meta($post->ID, '_pillen_dicke', true);
        $gewicht = get_post_meta($post->ID, '_pillen_gewicht', true);
        $bruchrille = get_post_meta($post->ID, '_pillen_bruchrille', true);
        $inhalt = get_post_meta($post->ID, '_pillen_inhalt', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="pillen_datum"><?php _e('Datum', 'pillen'); ?></label></th>
                <td>
                    <input type="date" id="pillen_datum" name="pillen_datum"
                           value="<?php echo esc_attr($datum); ?>" class="regular-text">
                    <p class="description"><?php _e('Datum der Analyse', 'pillen'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="pillen_durchmesser"><?php _e('Durchmesser', 'pillen'); ?></label></th>
                <td>
                    <input type="text" id="pillen_durchmesser" name="pillen_durchmesser"
                           value="<?php echo esc_attr($durchmesser); ?>" class="regular-text">
                    <p class="description"><?php _e('z.B. "9.1 mm" oder "10.1 x 10.2 mm"', 'pillen'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="pillen_dicke"><?php _e('Dicke', 'pillen'); ?></label></th>
                <td>
                    <input type="text" id="pillen_dicke" name="pillen_dicke"
                           value="<?php echo esc_attr($dicke); ?>" class="regular-text">
                    <p class="description"><?php _e('z.B. "4.5 mm"', 'pillen'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="pillen_gewicht"><?php _e('Gewicht', 'pillen'); ?></label></th>
                <td>
                    <input type="text" id="pillen_gewicht" name="pillen_gewicht"
                           value="<?php echo esc_attr($gewicht); ?>" class="regular-text">
                    <p class="description"><?php _e('z.B. "262.7 mg"', 'pillen'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="pillen_bruchrille"><?php _e('Bruchrille', 'pillen'); ?></label></th>
                <td>
                    <select id="pillen_bruchrille" name="pillen_bruchrille">
                        <option value=""><?php _e('-- Auswählen --', 'pillen'); ?></option>
                        <option value="Ja" <?php selected($bruchrille, 'Ja'); ?>><?php _e('Ja', 'pillen'); ?></option>
                        <option value="Nein" <?php selected($bruchrille, 'Nein'); ?>><?php _e('Nein', 'pillen'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="pillen_inhalt"><?php _e('Inhalt (Text)', 'pillen'); ?></label></th>
                <td>
                    <textarea id="pillen_inhalt" name="pillen_inhalt" rows="3"
                              class="large-text"><?php echo esc_textarea($inhalt); ?></textarea>
                    <p class="description"><?php _e('z.B. "35.1 mg m-CPP + 8.9 mg Metoclopramid"', 'pillen'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render Composition Meta Box
     */
    public function render_composition_meta_box($post) {
        $composition = get_post_meta($post->ID, '_pillen_composition', true);
        if (empty($composition)) {
            $composition = array();
        }
        ?>
        <div id="pillen-composition-container">
            <table class="widefat">
                <thead>
                    <tr>
                        <th><?php _e('Wirkstoff', 'pillen'); ?></th>
                        <th><?php _e('Menge', 'pillen'); ?></th>
                        <th><?php _e('Aktion', 'pillen'); ?></th>
                    </tr>
                </thead>
                <tbody id="composition-rows">
                    <?php
                    if (!empty($composition) && is_array($composition)) {
                        $i = 0;
                        foreach ($composition as $substance => $amount) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="composition_substance[]"
                                           value="<?php echo esc_attr($substance); ?>"
                                           placeholder="z.B. MDMA" class="regular-text">
                                </td>
                                <td>
                                    <input type="text" name="composition_amount[]"
                                           value="<?php echo esc_attr($amount); ?>"
                                           placeholder="z.B. 127.4 mg" class="regular-text">
                                </td>
                                <td>
                                    <button type="button" class="button remove-composition-row"><?php _e('Entfernen', 'pillen'); ?></button>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
            <p>
                <button type="button" id="add-composition-row" class="button"><?php _e('Wirkstoff hinzufügen', 'pillen'); ?></button>
            </p>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('#add-composition-row').on('click', function() {
                var row = '<tr>' +
                    '<td><input type="text" name="composition_substance[]" placeholder="z.B. MDMA" class="regular-text"></td>' +
                    '<td><input type="text" name="composition_amount[]" placeholder="z.B. 127.4 mg" class="regular-text"></td>' +
                    '<td><button type="button" class="button remove-composition-row"><?php _e('Entfernen', 'pillen'); ?></button></td>' +
                    '</tr>';
                $('#composition-rows').append(row);
            });

            $(document).on('click', '.remove-composition-row', function() {
                $(this).closest('tr').remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Render Images Meta Box
     */
    public function render_images_meta_box($post) {
        $image_ids = get_post_meta($post->ID, '_pillen_image_ids', true);
        if (empty($image_ids)) {
            $image_ids = array();
        }
        ?>
        <div id="pillen-images-container">
            <div id="pillen-images-preview">
                <?php
                if (!empty($image_ids) && is_array($image_ids)) {
                    foreach ($image_ids as $img_id) {
                        $img = wp_get_attachment_image($img_id, 'thumbnail');
                        echo '<div class="pillen-image-item" data-id="' . esc_attr($img_id) . '">';
                        echo $img;
                        echo '<button type="button" class="button remove-image">×</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <input type="hidden" id="pillen_image_ids" name="pillen_image_ids" value="<?php echo esc_attr(implode(',', $image_ids)); ?>">
            <p>
                <button type="button" id="pillen-upload-images" class="button"><?php _e('Bilder hinzufügen', 'pillen'); ?></button>
            </p>
            <p class="description"><?php _e('Sie können mehrere Bilder der Pille hochladen.', 'pillen'); ?></p>
        </div>

        <style>
        #pillen-images-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }
        .pillen-image-item {
            position: relative;
            display: inline-block;
        }
        .pillen-image-item img {
            display: block;
        }
        .pillen-image-item .remove-image {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            line-height: 1;
            cursor: pointer;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            var frame;
            var imageIds = $('#pillen_image_ids').val() ? $('#pillen_image_ids').val().split(',') : [];

            $('#pillen-upload-images').on('click', function(e) {
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: '<?php _e('Pillen-Bilder auswählen', 'pillen'); ?>',
                    button: {
                        text: '<?php _e('Verwenden', 'pillen'); ?>'
                    },
                    multiple: true
                });

                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    attachments.forEach(function(attachment) {
                        if (imageIds.indexOf(String(attachment.id)) === -1) {
                            imageIds.push(attachment.id);
                            var imgHtml = '<div class="pillen-image-item" data-id="' + attachment.id + '">' +
                                '<img src="' + attachment.sizes.thumbnail.url + '" />' +
                                '<button type="button" class="button remove-image">×</button>' +
                                '</div>';
                            $('#pillen-images-preview').append(imgHtml);
                        }
                    });
                    $('#pillen_image_ids').val(imageIds.join(','));
                });

                frame.open();
            });

            $(document).on('click', '.remove-image', function() {
                var $item = $(this).closest('.pillen-image-item');
                var imgId = $item.data('id');
                imageIds = imageIds.filter(function(id) { return id != imgId; });
                $('#pillen_image_ids').val(imageIds.join(','));
                $item.remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Save Meta Boxes
     */
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['pillen_meta_box_nonce']) ||
            !wp_verify_nonce($_POST['pillen_meta_box_nonce'], 'pillen_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save simple fields
        $fields = array('datum', 'durchmesser', 'dicke', 'gewicht', 'bruchrille', 'inhalt');
        foreach ($fields as $field) {
            if (isset($_POST['pillen_' . $field])) {
                update_post_meta($post_id, '_pillen_' . $field, sanitize_text_field($_POST['pillen_' . $field]));
            }
        }

        // Save composition
        if (isset($_POST['composition_substance']) && isset($_POST['composition_amount'])) {
            $composition = array();
            $substances = $_POST['composition_substance'];
            $amounts = $_POST['composition_amount'];

            for ($i = 0; $i < count($substances); $i++) {
                $substance = sanitize_text_field($substances[$i]);
                $amount = sanitize_text_field($amounts[$i]);
                if (!empty($substance) && !empty($amount)) {
                    $composition[$substance] = $amount;
                }
            }
            update_post_meta($post_id, '_pillen_composition', $composition);
        }

        // Save image IDs
        if (isset($_POST['pillen_image_ids'])) {
            $image_ids = array_filter(array_map('intval', explode(',', $_POST['pillen_image_ids'])));
            update_post_meta($post_id, '_pillen_image_ids', $image_ids);
        }
    }
}
