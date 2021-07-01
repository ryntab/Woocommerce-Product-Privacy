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
	public function product_box_display($post)
	{
		$privacy_options = get_option('woocommerce_product_privacy_option_name');
		$reversal = ($privacy_options['reverse_mode'] == 'reverse_mode') ? false : true;
		$state = woo_product_privacy_Admin::product_visibility_get_state($post, $reversal);
		$button;
		$button .= '<label>';
		$button .= '<input type="hidden" value="off" name="'. $state['name'].'" class="checkbox" ' . $state['status'] . '>';
		$button .= '<input type="checkbox" value="on" name="'. $state['name'].'" class="checkbox" ' . $state['status'] . '>';
		$button .= $state['label'];
		$button .= '</label>';
		echo $button;	
	}

	/**
	 * Save product visibility toggle state.
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_save_state($post_id)
	{
		if($_POST["product_visibility_reversal"] != null){
			$key = 'product_visibility_reversal';
			$value = $_POST["product_visibility_reversal"];
		}
		if($_POST["product_visibility"] != null){
			$key = 'product_visibility';
			$value = $_POST["product_visibility"];
		}

		if ($key == null) return;

		$post_type = get_post_type($post_id);
		if ($post_type == 'product') {
			update_post_meta($post_id, $key, $value);
		}
	}

	/**
	 * Get product visibility toggle state callback
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_get_state($post, $reversal)
	{
		$visibility = ($reversal) ? 'product_visibility' : 'product_visibility_reversal';
		$state = get_post_meta($post->ID, $visibility, 'true');
		$state = array(
			"status" => ($state == 'on') ? 'checked' : '',
			"name" => ($reversal) ? 'product_visibility' : 'product_visibility_reversal',
			"label" => ($reversal) ? 'Prevent Public Access' : 'Allow Public Access',
		);
		return $state;
	}

	/**
	 * Set 404 callback
	 *
	 * @since    1.0.0
	 */
	public function product_force_four_oh_four()
	{
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		nocache_headers();
	}


	/**
	 * Set redirect for product page loading.
	 *
	 * @since    1.0.0
	 */
	public function product_visibility_load_action()
	{
		global $post;
		$post_type = get_post_type($post->id);

		if ($post_type != 'product') return;

		if (is_shop()) return;

		$privacy_options = get_option('woocommerce_product_privacy_option_name');

		if ($privacy_options['hide_everything'] == 'hide_everything'){
			woo_product_privacy_Admin::product_force_four_oh_four();
			return;
		}

		if ($privacy_options['reverse_mode'] == 'reverse_mode'){
			$meta = get_post_meta($post->ID, 'product_visibility_reversal', 'true');
			if ($meta == 'on') return;
			woo_product_privacy_Admin::product_force_four_oh_four();
			return;
		} 
	
		$state = get_post_meta($post->ID, 'product_visibility', 'true');
		if ($state == 'on' && !current_user_can('administrator')) {
			woo_product_privacy_Admin::product_force_four_oh_four();
		}
	}
}



class WoocommerceProductPrivacy
{
	private $woocommerce_product_privacy_options;

	public function __construct()
	{
		add_action('admin_menu', array($this, 'woocommerce_product_privacy_add_plugin_page'));
		add_action('admin_init', array($this, 'woocommerce_product_privacy_page_init'));
	}

	public function woocommerce_product_privacy_add_plugin_page()
	{
		add_options_page(
			'Woocommerce Product Privacy', // page_title
			'Woocommerce Product Privacy', // menu_title
			'manage_options', // capability
			'woocommerce-product-privacy', // menu_slug
			array($this, 'woocommerce_product_privacy_create_admin_page') // function
		);
	}

	public function woocommerce_product_privacy_create_admin_page()
	{
		$this->woocommerce_product_privacy_options = get_option('woocommerce_product_privacy_option_name'); ?>

		<div class="wrap">
			<?php settings_errors(); ?>

			<h2>Woocommerce Product Privacy</h2>
			<form method="post" action="options.php">
				<?php
				settings_fields('woocommerce_product_privacy_option_group');
				do_settings_sections('woocommerce-product-privacy-admin');
				submit_button();
				?>
			</form>
		</div>
<?php }

	public function woocommerce_product_privacy_page_init()
	{
		register_setting(
			'woocommerce_product_privacy_option_group', // option_group
			'woocommerce_product_privacy_option_name', // option_name
			array($this, 'woocommerce_product_privacy_sanitize') // sanitize_callback
		);

		add_settings_section(
			'woocommerce_product_privacy_setting_section', // id
			'Settings', // title
			array($this, 'woocommerce_product_privacy_section_info'), // callback
			'woocommerce-product-privacy-admin' // page
		);

		add_settings_field(
			'reverse_mode', // id
			'Reverse Mode', // title
			array($this, 'reverse_mode_callback'), // callback
			'woocommerce-product-privacy-admin', // page
			'woocommerce_product_privacy_setting_section' // section
		);

		add_settings_field(
			'hide_everything', // id
			'Hide Everything! 💣', // title
			array($this, 'hide_everything_callback'), // callback
			'woocommerce-product-privacy-admin', // page
			'woocommerce_product_privacy_setting_section' // section
		);

		add_settings_field(
			'allow_traffic_from_referral_urls_2', // id
			'Allow traffic from referral URLs', // title
			array($this, 'allow_traffic_from_referral_urls_2_callback'), // callback
			'woocommerce-product-privacy-admin', // page
			'woocommerce_product_privacy_setting_section' // section
		);
	}

	public function woocommerce_product_privacy_sanitize($input)
	{
		$sanitary_values = array();
		if (isset($input['reverse_mode'])) {
			$sanitary_values['reverse_mode'] = $input['reverse_mode'];
		}

		if (isset($input['hide_everything'])) {
			$sanitary_values['hide_everything'] = $input['hide_everything'];
		}

		if (isset($input['allow_traffic_from_referral_urls_2'])) {
			$sanitary_values['allow_traffic_from_referral_urls_2'] = esc_textarea($input['allow_traffic_from_referral_urls_2']);
		}

		return $sanitary_values;
	}

	public function woocommerce_product_privacy_section_info()
	{
	}

	public function reverse_mode_callback()
	{
		printf(
			'<input type="checkbox" name="woocommerce_product_privacy_option_name[reverse_mode]" id="reverse_mode" value="reverse_mode" %s> <label for="reverse_mode">Instead of selecting which products to hide, select which products to show.</label>',
			(isset($this->woocommerce_product_privacy_options['reverse_mode']) && $this->woocommerce_product_privacy_options['reverse_mode'] === 'reverse_mode') ? 'checked' : ''
		);
	}

	public function hide_everything_callback()
	{
		printf(
			'<input type="checkbox" name="woocommerce_product_privacy_option_name[hide_everything]" id="hide_everything" value="hide_everything" %s> <label for="hide_everything">No products will be publicly accessible by their direct links. This will override any rules set on individual products.</label>',
			(isset($this->woocommerce_product_privacy_options['hide_everything']) && $this->woocommerce_product_privacy_options['hide_everything'] === 'hide_everything') ? 'checked' : ''
		);
	}

	public function allow_traffic_from_referral_urls_2_callback()
	{
		printf(
			'<textarea class="large-text" rows="5" name="woocommerce_product_privacy_option_name[allow_traffic_from_referral_urls_2]" id="allow_traffic_from_referral_urls_2">%s</textarea>',
			isset($this->woocommerce_product_privacy_options['allow_traffic_from_referral_urls_2']) ? esc_attr($this->woocommerce_product_privacy_options['allow_traffic_from_referral_urls_2']) : ''
		);
	}
}
if (is_admin())
	$woocommerce_product_privacy = new WoocommerceProductPrivacy();
