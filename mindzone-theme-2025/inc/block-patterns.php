<?php
/**
 * Block Patterns
 *
 * @package Mindzone
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register block pattern categories
 */
function mindzone_register_pattern_categories() {
    register_block_pattern_category('mindzone', array(
        'label' => __('Mindzone', 'mindzone'),
    ));
}
add_action('init', 'mindzone_register_pattern_categories');
