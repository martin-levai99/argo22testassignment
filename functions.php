<?php 

/*--------------------------------
	Theme support & enqueue assets in FSE editor
--------------------------------*/
if ( ! function_exists( 'fse_support_and_assets' ) )  {	
	/**
	 * Add support for blocks and editor style
	 *
	 * @return void
	 */
	function boostit_support() {
	
		// Add support for block style - must have otherwise default WP blocks will not have CSS
		add_theme_support( 'wp-block-styles' ); 
	
		// Add theme support for adding custom CSS into site editor and add styles to website in FSE editor
		// + add bootstrap (customized) css
		add_theme_support( 'editor-styles' );	
		add_editor_style( '/assets/css/bootstrap.min.css' );

		// Disable default WP patterns (just looks bad)
		remove_theme_support( 'core-block-patterns' );
	}
}
add_action( 'after_setup_theme', 'fse_support_and_assets' );


/*--------------------------------
	Enqueue assets (front-end)
--------------------------------*/
if ( ! function_exists( 'theme_frontend_assets' ) )  {	
	/**
	 * Enqueue custom styles
	 *
	 * @return void
	 */
	function boostit_assets() {

		// Deregister jquery (not needed)
		if ( ! is_admin() ) wp_deregister_script( 'jquery' );
	
		// Core CSS file
		wp_enqueue_style("core", get_stylesheet_uri(), array(), wp_get_theme()->get("Version") );

		// Bootstrap styles
		wp_enqueue_style("bootstrap", get_template_directory_uri() . "/assets/css/bootstrap.min.css", array(), wp_get_theme()->get("Version") );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_frontend_assets');



/*--------------------------------
	Register blocks
--------------------------------*/
add_action( 'init', 'register_acf_blocks', 5 );
function register_acf_blocks() {
	register_block_type( __DIR__ . '/blocks/widgets/advanced-slider' );
}


/*--------------------------------
	Include additional files
--------------------------------*/
include_once("./helpers/native_wp_block_settings.php");