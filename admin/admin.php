<?php
class Advanced_Ads_Genesis_Admin {

	/**
	 * holds base class
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
	 * load actions and filters
	 */
	public function wp_admin_plugins_loaded(){

		if( ! class_exists( 'Advanced_Ads_Admin', false ) ) {
			// show admin notice
			add_action( 'admin_notices', array( $this, 'missing_plugin_notice' ) );

			return;
		}

		// add sticky placement
		add_action( 'advanced-ads-placement-types', array( $this, 'add_placement' ) );

		// content of sticky placement
		add_action( 'advanced-ads-placement-options-after', array( $this, 'placement_options' ), 10, 2 );

	}

	/**
	 * show warning if Advanced Ads js is not activated
	 */
	public function missing_plugin_notice(){
		echo '<div class="error"><p>' . sprintf( __( '<strong>Advanced Ads – Genesis Ads</strong> is an extension for the Advanced Ads plugin. Please visit <a href="%s" target="_blank" >wpadvancedads.com</a> to download it for free.', 'genesis-ads' ), 'https://wpadvancedads.com' ) . '</p></div>';
	}

	/**
	 * add placement
	 *
	 * @since 1.0.0
	 * @param arr $types existing placements
	 * @return arr $types
	 */
	public function add_placement( $types ){

		// fixed header bar
		$types['genesis'] = array(
		    'title' => __( 'Genesis Positions', 'genesis-ads' ),
		    'description' => __( 'Various positions for the Genesis theme.', 'genesis-ads' ),
		    'image' => AAG_BASE_URL . 'admin/assets/img/genesis.png',
		);

		return $types;
	}

	/**
	 * options for the placement
	 *
	 * @since 1.0.0
	 * @param string $placement_slug id of the placement
	 * @param arr $placement current placement
	 */
	public function placement_options( $placement_slug = '', $placement = array() ){
		if( 'genesis' === $placement['type'] ){
			$genesis_positions = $this->get_genesis_hooks();
			$current = isset($placement['options']['genesis_hook']) ? $placement['options']['genesis_hook'] : '';

			// warning if no Genesis theme installed
			if( !defined( 'PARENT_THEME_NAME') || 'Genesis' !== PARENT_THEME_NAME ) :
			    ?><p class="advads-error-message"><?php echo __('No Genesis theme detected', 'genesis-ads' ); ?></p><?php
			endif;

			?><label><?php _e( 'position', 'genesis-ads' ); ?></label>
			<select name="advads[placements][<?php echo $placement_slug; ?>][options][genesis_hook]">
			    <option>---</option>
			    <?php foreach( $genesis_positions as $_group => $_positions ) : ?>
				<optgroup label="<?php echo $_group; ?>">
				<?php foreach( $_positions as $_position ) : ?>
				<option <?php selected( $_position, $current ); ?>><?php echo $_position; ?></option>
			    <?php endforeach; ?>
				</optgroup>
			    <?php endforeach; ?>
			</select>
			<p class="description"><?php printf( __('You can find an explanation of the hooks in the <a href="%s" target="_blank">Genesis Hook Reference</a>', 'genesis-ads' ), 'http://my.studiopress.com/docs/hook-reference/' ); ?></p>
			    <?php
		}
	}

	/**
	 * get list of genesis hooks with hook > title
	 *
	 * @since 1.0.0
	 * @return arr $positions
	 */
	public function get_genesis_hooks(){
		// list of all hooks http://my.studiopress.com/docs/hook-reference/#structural-action-hooks
		// only used the ones for public output in frontend here
		return array(
			__( 'Header', 'genesis-ads' ) => array(
			    'before_header',
			    'header',
			    'after_header',
			    'site_title',
			    'site_description',
			),
			__( 'Wrapper', 'genesis-ads' ) => array(
			    'before_content_sidebar_wrap',
			    'after_content_sidebar_wrap',
			    'before_content',
			    'after_content',
			),
			__( 'Sidebar', 'genesis-ads' ) => array(
			    'sidebar',
			    'before_sidebar_widget_area',
			    'after_sidebar_widget_area',
			    'sidebar_alt',
			    'before_sidebar_alt_widget_area',
			    'after_sidebar_alt_widget_area',
			),
			__( 'Loop', 'genesis-ads' ) => array(
			    'before_loop',
			    'loop',
			    'after_loop',
			    'after_endwhile',
			    'loop_else',
			),
			__( 'Content', 'genesis-ads' ) => array(
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
			__( 'Comments & Pings', 'genesis-ads' ) => array(
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
			    'after_comment_form'
			),
			__( 'Footer', 'genesis-ads' ) => array(
			    'before_footer',
			    'footer',
			    'after_footer',
			),
		);
	}
}
