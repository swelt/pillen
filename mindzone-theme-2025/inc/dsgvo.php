<?php
/**
 * DSGVO / Privacy Compliance Functions
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable Google Fonts (use local fonts instead)
 */
function mindzone_disable_google_fonts() {
    // Already handled by using system fonts in theme.json
}

/**
 * Make YouTube embeds DSGVO compliant (youtube-nocookie.com)
 */
function mindzone_youtube_privacy_mode($html, $url) {
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        $html = str_replace('youtube.com', 'youtube-nocookie.com', $html);
        $html = str_replace('src="', 'loading="lazy" src="', $html);
    }
    return $html;
}
add_filter('embed_oembed_html', 'mindzone_youtube_privacy_mode', 10, 2);

/**
 * Remove WordPress version from head (security)
 */
remove_action('wp_head', 'wp_generator');

/**
 * Disable XML-RPC (security)
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove jQuery Migrate (performance)
 */
function mindzone_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'mindzone_remove_jquery_migrate');

/**
 * Disable Emojis (performance + privacy)
 */
function mindzone_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'mindzone_disable_emojis');

/**
 * Add privacy policy text
 */
function mindzone_privacy_policy_text() {
    return sprintf(
        __('Diese Website nutzt keine Tracking-Tools von Drittanbietern. Weitere Informationen findest du in unserer %s.', 'mindzone'),
        '<a href="' . get_privacy_policy_url() . '">' . __('Datenschutzerklärung', 'mindzone') . '</a>'
    );
}

/**
 * Cookie consent notice (simple, no external service)
 */
function mindzone_cookie_notice() {
    if (!isset($_COOKIE['mindzone_cookie_consent'])) {
        ?>
        <div id="cookie-notice" class="cookie-notice" style="display: none;">
            <div class="cookie-notice-container">
                <p>
                    <?php echo mindzone_privacy_policy_text(); ?>
                </p>
                <button id="cookie-accept" class="button">
                    <?php _e('Verstanden', 'mindzone'); ?>
                </button>
            </div>
        </div>
        <script>
        (function() {
            var notice = document.getElementById('cookie-notice');
            var acceptBtn = document.getElementById('cookie-accept');

            // Show notice
            notice.style.display = 'block';

            // Accept button
            acceptBtn.addEventListener('click', function() {
                document.cookie = 'mindzone_cookie_consent=1; path=/; max-age=31536000; SameSite=Lax';
                notice.style.display = 'none';
            });
        })();
        </script>
        <?php
    }
}
add_action('wp_footer', 'mindzone_cookie_notice');

/**
 * Add rel="noopener" to external links (security)
 */
function mindzone_add_noopener($content) {
    $content = str_replace('target="_blank"', 'target="_blank" rel="noopener noreferrer"', $content);
    return $content;
}
add_filter('the_content', 'mindzone_add_noopener');

/**
 * Anonymize IP addresses in WordPress (if using any logging)
 */
function mindzone_anonymize_ip($ip) {
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
    }

    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return preg_replace('/\.\d+$/', '.0', $ip);
    }

    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return preg_replace('/:[0-9a-f]+$/i', ':0', $ip);
    }

    return $ip;
}
