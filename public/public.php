<?php

class Advanced_Ads_Genesis {

        /**
         * holds plugin base class
         *
         * @var Advanced_Ads_Slider_Plugin
         * @since 1.0.0
         */
        protected $plugin;

        /**
         * Initialize the plugin
         * and styles.
         *
         * @since     1.0.0
         */
        public function __construct() {

                $this->plugin = Advanced_Ads_Genesis_Plugin::get_instance();

		add_action( 'plugins_loaded', array( $this, 'wp_plugins_loaded_ad_actions' ), 20 );
        }

	/**
	 * load actions and filters needed only for ad rendering
	 *  this will make sure options get loaded for ajax and non-ajax-calls
	 *
	 * @since 1.0.0
	 */
	public function wp_plugins_loaded_ad_actions(){
		// stop, if main plugin doesn’t exist
		if ( ! class_exists( 'Advanced_Ads', false ) ) {
			return ;
		}

		// load the dynamic genesis hooks

		// get placements
		$placements = get_option( 'advads-ads-placements', array() );

		foreach ( $placements as $_placement_id => $_placement ){
		    if ( isset($_placement['type']) && 'genesis' == $_placement['type'] && isset( $_placement['options']['genesis_hook'] ) ){
			    $hook = 'genesis_' . $_placement['options']['genesis_hook'];
			    add_action( $hook, array($this, 'execute_hook') );
		    }
		}
	}

	/**
	 * execute genesis hooks
	 *
	 * @since 1.0.0
	 */
	public function execute_hook(){

		// get placements
		$placements = get_option( 'advads-ads-placements', array() );

		// look for the current hook in the placements
		$hook = current_filter();
		foreach ( $placements as $_placement_id => $_placement ){
		    if ( isset($_placement['type']) && 'genesis' == $_placement['type']
			    && isset( $_placement['options']['genesis_hook'] )
			    && $hook === 'genesis_' . $_placement['options']['genesis_hook'] ){
				the_ad_placement( $_placement_id );
		    }
		}

	}


}
