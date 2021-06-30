<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ryntab.com
 * @since      1.0.0
 *
 * @package    woo_product_privacy
 * @subpackage woo_product_privacy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    woo_product_privacy
 * @subpackage woo_product_privacy/public
 * @author     Your Name <email@example.com>
 */
class woo_product_privacy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woo_product_privacy    The ID of this plugin.
	 */
	private $woo_product_privacy;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $woo_product_privacy       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $woo_product_privacy, $version ) {

		$this->woo_product_privacy = $woo_product_privacy;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in woo_product_privacy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The woo_product_privacy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in woo_product_privacy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The woo_product_privacy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


	}

}
