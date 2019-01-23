<?php
class Advanced_Ads_Genesis_Admin {

	/**
	 * Holds base class.
	 *
	 * @var Advanced_Ads_Genesis_Plugin
	 * @since 1.0.0
	 */
	protected $plugin;

	const PLUGIN_LINK = 'http://wpadvancedads.com/add-ons/genesis/';

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	public function __construct() {

		$this->plugin = Advanced_Ads_Genesis_Plugin::get_instance();

		add_action( 'plugins_loaded', array( $this, 'wp_admin_plugins_loaded' ) );

	}

	/**
	 * Load actions and filters
	 */
	public function wp_admin_plugins_loaded() {

		if ( ! class_exists( 'Advanced_Ads_Admin', false ) ) {
			// Show admin notice.
			add_action( 'admin_notices', array( $this, 'missing_plugin_notice' ) );

			return;
		}

		// Add sticky placement.
		add_action( 'advanced-ads-placement-types', array( $this, 'add_placement' ) );

		// Content of sticky placement.
		add_action( 'advanced-ads-placement-options-after', array( $this, 'placement_options' ), 10, 2 );

	}

	/**
	 * Show warning if Advanced Ads js is not activated.
	 */
	public function missing_plugin_notice() {
		$plugins = get_plugins();
		if( isset( $plugins['advanced-ads/advanced-ads.php'] ) ){ // is installed, but not active
			$link = '<a class="button button-primary" href="' . wp_nonce_url( 'plugins.php?action=activate&amp;plugin=advanced-ads/advanced-ads.php&amp', 'activate-plugin_advanced-ads/advanced-ads.php' ) . '">'. __('Activate Now', 'advanced-ads-genesis') .'</a>';
		} else {
			$link = '<a class="button button-primary" href="' . wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . 'advanced-ads'), 'install-plugin_' . 'advanced-ads') . '">'. __('Install Now', 'advanced-ads-genesis') .'</a>';
		}
		echo '<div class="error"><p>' . sprintf(__('<strong>%s</strong> requires the <strong><a href="https://wpadvancedads.com/#utm_source=advanced-ads&utm_medium=link&utm_campaign=activate-genesis" target="_blank">Advanced Ads</a></strong> plugin to be installed and activated on your site.', 'advanced-ads-genesis'), 'Genesis Ads') 
			. '&nbsp;' . $link . '</p></div>';
	}

	/**
	 * Add placement
	 *
	 * @since 1.0.0
	 * @param arr $types existing placements
	 * @return arr $types
	 */
	public function add_placement( $types ) {

		// Fixed header bar.
		$types['genesis'] = array(
			'title'       => __( 'Genesis Positions', 'advanced-ads-genesis' ),
			'description' => __( 'Various positions for the Genesis theme.', 'advanced-ads-genesis' ),
			'image'       => AAG_BASE_URL . 'admin/assets/img/genesis.png',
		);

		return $types;
	}

	/**
	 * Options for the placement.
	 *
	 * @since 1.0.0
	 * @param string $placement_slug id of the placement
	 * @param arr $placement current placement
	 */
	public function placement_options( $placement_slug = '', $placement = array() ) {
		if ( 'genesis' === $placement['type'] ) {
			$genesis_positions = $this->get_genesis_hooks();
			$current           = isset( $placement['options']['genesis_hook'] ) ? $placement['options']['genesis_hook'] : '';

			// Warning if no Genesis theme installed.
			if ( ! defined( 'PARENT_THEME_NAME' ) || 'Genesis' !== PARENT_THEME_NAME ) :
				?><p class="advads-error-message"><?php echo __( 'No Genesis theme detected', 'advanced-ads-genesis' ); ?></p>
			<?php endif; ?>
			<label><?php _e( 'position', 'advanced-ads-genesis' ); ?></label>
			<select name="advads[placements][<?php echo $placement_slug; ?>][options][genesis_hook]">
				<option>---</option>
				<?php foreach ( $genesis_positions as $_group => $_positions ) : ?>
				<optgroup label="<?php echo $_group; ?>">
				<?php foreach ( $_positions as $_position ) : ?>
				<option <?php selected( $_position, $current ); ?>><?php echo $_position; ?></option>
				<?php endforeach; ?>
				</optgroup>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php printf( __( 'You can find an explanation of the hooks in the <a href="%s" target="_blank">Genesis Hook Reference</a>', 'advanced-ads-genesis' ), 'http://my.studiopress.com/docs/hook-reference/' ); ?></p>
				<?php
		}
	}

	/**
	 * Get list of genesis hooks with hook > title
	 *
	 * @since 1.0.0
	 * @return arr $positions
	 */
	public function get_genesis_hooks() {
		// List of all hooks http://my.studiopress.com/docs/hook-reference/#structural-action-hooks
		// Only used the ones for public output in frontend here.
		return array(
			__( 'Header', 'advanced-ads-genesis' )  => array(
				'before_header',
				'header',
				'after_header',
				'site_title',
				'site_description',
			),
			__( 'Wrapper', 'advanced-ads-genesis' ) => array(
				'before_content_sidebar_wrap',
				'after_content_sidebar_wrap',
				'before_content',
				'after_content',
			),
			__( 'Sidebar', 'advanced-ads-genesis' ) => array(
				'sidebar',
				'before_sidebar_widget_area',
				'after_sidebar_widget_area',
				'sidebar_alt',
				'before_sidebar_alt_widget_area',
				'after_sidebar_alt_widget_area',
			),
			__( 'Loop', 'advanced-ads-genesis' )    => array(
				'before_loop',
				'loop',
				'after_loop',
				'after_endwhile',
				'loop_else',
			),
			__( 'Content', 'advanced-ads-genesis' ) => array(
				'before_entry',
				'after_entry',
				'entry_header',
				'before_entry_content',
				'entry_content',
				'after_entry_content',
				'entry_footer',
				'before_post',
				'after_post',
				'before_post_title',
				'post_title',
				'after_post_title',
				'before_post_content',
				'post_content',
				'after_post_content',
			),
			__( 'Comments & Pings', 'advanced-ads-genesis' ) => array(
				'before_comments',
				'comments',
				'after_comments',
				'list_comments',
				'before_pings',
				'pings',
				'after_pings',
				'list_pings',
				'before_comment',
				'after_comment',
				'before_comment_form',
				'comment_form',
				'after_comment_form',
			),
			__( 'Footer', 'advanced-ads-genesis' )  => array(
				'before_footer',
				'footer',
				'after_footer',
			),
		);
	}
}
