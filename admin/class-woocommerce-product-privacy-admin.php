<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ryntab.com
 * @since      1.0.0
 *
 * @package    woo_product_privacy
 * @subpackage woo_product_privacy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    woo_product_privacy
 * @subpackage woo_product_privacy/admin
 * @author     Your Name <email@example.com>
 */
class woo_product_privacy_Admin
{

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
	 * @param      string    $woo_product_privacy       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($woo_product_privacy, $version)
	{

		$this->woo_product_privacy = $woo_product_privacy;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->woo_product_privacy, plugin_dir_url(__FILE__) . 'css/woocommerce-product-privacy-admin.css', array(), $this->version, 'all');
	}


	/**
	 * Add product visibility toggle to product edit pages.
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_meta_box($post_type)
	{
		$post_types = array('product');
		if (in_array($post_type, $post_types)) {
			add_meta_box('woo-product-privacy', __('Product Privacy', 'Woo Product Privacy'), array($this, 'product_box_display'), 'product', 'side', 'high');
		}
	}

	/**
	 * Add product visibility toggle callback.
	 *
	 * @since    1.0.0
	 */
    public function product_box_display($post){
		$state = woo_product_privacy_Admin::product_visibility_get_state($post);
		$button;
		$button .= '<label>';
		$button .= '<input name="product_visibility" type="checkbox" class="checkbox" '. $state .'>';
		$button .= 'Prevent Public Access';
		$button .= '</label>';
		echo $button;
    }

	/**
	 * Save product visibility toggle state.
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_save_state($post_id){
		$post_type = get_post_type($post_id);
		if($post_type == 'product') {
			update_post_meta($post_id,'product_visibility', $_POST["product_visibility"]);
		}
	}

	/**
	 * Get product visibility toggle state callback
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_get_state($post){
		$state = get_product($post->ID);
		$state = get_post_meta($post->ID, 'product_visibility','true');
		$state = ($state == 'on') ? 'checked' : '';
		return $state;
	}

	/**
	 * Set redirect for product page loading.
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_load_action(){
		global $post;
		$state = get_post_meta($post->ID, 'product_visibility', 'true');
		if ($state == 'on' && !current_user_can('administrator')){
			woo_product_privacy_Admin::product_force_four_oh_four();
		}
	}

	/**
	 * Set 404 callback
	 *
	 * @since    1.0.0
	 */
	public function product_force_four_oh_four(){
		global $wp_query; //$posts (if required)
		$wp_query->set_404();
    	status_header( 404 );
    	nocache_headers();
	}

}