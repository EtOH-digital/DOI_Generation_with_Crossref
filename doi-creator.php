<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://etoh.digital
 * @since             1.0.0
 * @package           DOI Generation with Crossref
 *
 * @wordpress-plugin
 * Plugin Name:       DOI Generation with Crossref
 * Description:       A plugin to create and submit DOIs to the CROSSREF API.
 * Version:           504
 * Author:            EtOH Digital 
 * Author URI:        https://etoh.digital
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       doi-generation-with-crossref
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

#--------------------------------------------
# add by EtOH_Digital
#--------------------------------------------
if( !function_exists('pr') ){

	function pr($data){

		echo '<pre>';
		print_r($data);
		echo '<pre>';

	}
}

#--------------------------------------------
# end add by EtOH_Digital
#--------------------------------------------


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DOI_CREATOR_VERSION', '1.0.0' );
define( 'DOI_CREATOR_SLUG', 'doi-creator' );
define( 'DOI_CREATOR_FILE', __FILE__ );
define( 'DOI_CREATOR_URL', plugin_dir_url( __FILE__ ) );
define( 'DOI_CREATOR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-doi-creator-activator.php
 */
function activate_doi_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator-activator.php';
	Doi_Creator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-doi-creator-deactivator.php
 */
function deactivate_doi_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator-deactivator.php';
	Doi_Creator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_doi_creator' );
register_deactivation_hook( __FILE__, 'deactivate_doi_creator' );




#--------------------------------------------
# add by EtOH_Digital
#--------------------------------------------

require_once plugin_dir_path( __FILE__ ) . 'wl_modules/wl_helpers.php';
require_once plugin_dir_path( __FILE__ ) . 'wl_modules/admin_post_module.php';
require_once plugin_dir_path( __FILE__ ) . 'wl_modules/Frontend_Post_Module.php';
require_once plugin_dir_path( __FILE__ ) . 'wl_modules/Numbers_Words/Numbers/Words.php';
require_once plugin_dir_path( __FILE__ ) . 'wl_modules/admin_page_plugin_list.php';
require_once plugin_dir_path( __FILE__ ) . 'wl_modules/wp_core_heartbit_module.php';
// use as $i = 100; .Numbers_Words::toWords($i, 'en_US');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */


require plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator.php';

function wl_doi_new_modules(){
	Admin_Post_Module::get_instance();
	Frontend_Post_Module::get_instance();
	admin_page_plugin_list::get_instance();
	wp_core_heartbit_module::get_instance();

	// $res = Admin_Post_Module::get_instance()->create_or_get_doi(1111);
	// experiment();

}


add_action( 'plugins_loaded', 'wl_doi_new_modules' );

#-------------------------------------------------------------


function experiment(){

	$post_id = 37718;


	$res = get_post_meta( $post_id, 'doi_response', true );

	$res1 = json_decode( $res, true );

	pr($res1); die();


}

#--------------------------------------------------------------------------

#--------------------------------------------
# end add by EtOH_Digital
#--------------------------------------------


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_doi_creator() {

	$plugin = new Doi_Creator();
	$plugin->run();

}
run_doi_creator();
