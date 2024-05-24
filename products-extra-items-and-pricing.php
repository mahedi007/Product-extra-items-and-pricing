<?php
/**
 * Plugin Name: Product Extra Items and Pricing
 * Description: A plugin that allows customers to add extra items with pricing for products.
 * Version: 1.0.0
 * Author: Mahedi Hasan
 * Text Domain: products-extra-items-and-pricing
 */


if (!defined('ABSPATH')) {
    exit;
}

define('PAE_PLUGIN_DIR', plugin_dir_path(__FILE__));

include_once PAE_PLUGIN_DIR . 'admin.php';
include_once PAE_PLUGIN_DIR . 'public.php';

register_activation_hook(__FILE__, 'pae_activate');
register_deactivation_hook(__FILE__, 'pae_deactivate');

function pae_activate() {
    // Actions to perform on plugin activation
}

function pae_deactivate() {
    // Actions to perform on plugin deactivation
}

function pae_init() {
    load_plugin_textdomain('products-extra-items-and-pricing', false, basename(dirname(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'pae_init');
