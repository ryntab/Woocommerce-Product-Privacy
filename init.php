<?php

/**
 * @link              http://ryntab.com
 * @since             1.0.0
 * @package           Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Product Privacy 🔒
 * Plugin URI:        https://www.ryntab.com/
 * Description:       Hide woocommerce products from being displayed publically.
 * Author:            Ryan Taber
 * Author URI:        https://www.ryntab.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-product-privacy
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WOO_PRODUCT_PRIVACY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-product-privacy-activator.php
 */
function activate_woo_product_privacy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-privacy-activator.php';
	woo_product_privacy_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-product-privacy-deactivator.php
 */
function deactivate_woo_product_privacy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-privacy-deactivator.php';
	woo_product_privacy_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_product_privacy' );
register_deactivation_hook( __FILE__, 'deactivate_woo_product_privacy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-product-privacy.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_product_privacy() {

	$plugin = new woo_product_privacy();
	$plugin->run();

}
run_woo_product_privacy();
