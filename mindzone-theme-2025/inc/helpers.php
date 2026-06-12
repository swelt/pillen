<?php
/**
 * Helper Functions
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get warning level badge HTML
 */
function mindzone_get_warning_badge($level) {
    $badges = array(
        'Hoch' => '<span class="badge badge-danger">⚠️ ' . __('Hoch', 'mindzone') . '</span>',
        'Mittel' => '<span class="badge badge-warning">⚡ ' . __('Mittel', 'mindzone') . '</span>',
        'Niedrig' => '<span class="badge badge-info">ℹ️ ' . __('Niedrig', 'mindzone') . '</span>',
    );

    return isset($badges[$level]) ? $badges[$level] : '';
}

/**
 * Format date in German style
 */
function mindzone_format_date($date, $format = 'd.m.Y') {
    return date_i18n($format, strtotime($date));
}

/**
 * Get source attribution HTML
 */
function mindzone_get_source_attribution($source_name, $source_url = '') {
    $html = '<div class="source-attribution">';
    $html .= '<p>';
    $html .= '📍 ' . __('Quelle:', 'mindzone') . ' ';

    if ($source_url) {
        $html .= '<a href="' . esc_url($source_url) . '" target="_blank" rel="noopener">' . esc_html($source_name) . '</a>';
    } else {
        $html .= esc_html($source_name);
    }

    $html .= '</p>';
    $html .= '<p class="legal-notice">';
    $html .= '⚖️ ' . __('Drug Checking ist in Deutschland nicht legal. Diese Information stammt von unseren Partner-Organisationen in der Schweiz/Österreich.', 'mindzone');
    $html .= '</p>';
    $html .= '</div>';

    return $html;
}

/**
 * Get reading time estimate
 */
function mindzone_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average 200 words per minute

    return sprintf(__('%d Min. Lesezeit', 'mindzone'), $reading_time);
}
