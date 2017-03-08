<?php

include_once WP_CONTENT_DIR . '/wpalchemy/MetaBox.php';

// global styles for the meta boxes
if (is_admin()) add_action('admin_enqueue_scripts', 'metabox_style');

function metabox_style() {
	wp_enqueue_style('wpalchemy-metabox', get_stylesheet_directory_uri() . '/metaboxes/meta.css');
}

// include_once CELL_STORE_PATH . '/wpalchemy/MetaBox.php';

// // global styles for the meta boxes
// if (is_admin()) add_action('admin_enqueue_scripts', 'metabox_style');

// function metabox_style() {
// 	wp_enqueue_style('wpalchemy-metabox', plugins_url() . '/cell-store/wpalchemy/meta.css');
// }

/* eof */