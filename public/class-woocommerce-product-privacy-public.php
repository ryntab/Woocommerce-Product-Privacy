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
}
