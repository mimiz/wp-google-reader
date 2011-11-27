<?php
/*
Plugin Name: Google Reader
Dependencies:wp-zff-zend-framework-full/wp_zff_zend_framework_full.php
Plugin URI: 
Description: Integrate your Google Reader items to your blog sidebar
Version: 1.1
Author: Rémi Goyard
Author URI: http://www.mimiz.fr/
*/
require_once "WidgetGoogleReader.php";

/**
 * Add function to widgets_init that will load the widget.
 */
//add_action( 'widgets_init', 'WidgetGoogleReader_load_widgets' );
add_action('widgets_init', create_function('', 'return register_widget("WidgetGoogleReader");'));
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'google-reader',null, $plugin_dir .'/lang');
add_action( 'init', '_init_google_reader_core_library' );


set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) );
// We need WP-ZFF Zend Framework Full Plugin
function _init_google_reader_core_library()
{
	if (!defined('WP-ZFF')) {
		add_action('admin_notices', '_phpversion_google_reader_core_library');
	}else{
		if (!defined('WP-GRCORE_LIBRARY')) {
			$zfautoload = Zend_Loader_Autoloader::getInstance();
			$zfautoload->registerNamespace('Core_');
			define('WP-GRCORE_LIBRARY', true);
		}
		
	}
}



function _phpversion_google_reader_core_library($mess = '') { 
	if($mess == '')
	{   
		$mess = __("GOOGLE READER  : Plugin WP-ZFF Zend Framework Full is needed please install it !", 'google-reader');
	}
	echo "<div class='error'><p><strong>$mess</strong></p></div>";
}

/**
 * Admin specific devs
 */
if ( is_admin() ) {// instruction to only load if it is not the admin area
	require_once "GoogleReaderSettingsValidator.php";
   // register your script location, dependencies and version
   wp_register_script('custom_script',
		 WP_PLUGIN_URL . '/google-reader/js/custom_script.js',
		 array('jquery')
   );
   // enqueue the script
   wp_enqueue_script('custom_script');
   add_action('admin_menu', 'mt_add_pages');
   
   function mt_add_pages() {
   	 // Add a new submenu under Settings:
   	add_options_page(__('Google Reader', 'google-reader'), __('Google Reader', 'google-reader'), 'manage_options', 'googlereadersettings', 'google_reader_settings_page');
   }
	function google_reader_settings_page() {
	   include_once(dirname(__FILE__).'/options.php');
	}
	
	add_action( 'admin_init', 'register_google_reader_settings' );
	function register_google_reader_settings() { // whitelist options
	  register_setting( 'google-reader', 'googlereaderlogin', 'GoogleReaderSettingsValidator::login');
	  register_setting( 'google-reader', 'googlereaderpassword', 'GoogleReaderSettingsValidator::password');
	  register_setting( 'google-reader', 'googlereadercachedir', 'GoogleReaderSettingsValidator::cachedir' );
	  register_setting( 'google-reader', 'googlereadercachelifetime', 'GoogleReaderSettingsValidator::cachelifetime' );
	}
	add_action( 'admin_head-settings_page_googlereadersettings', 'add_help_to_options' );
	function add_help_to_options() {
		add_contextual_help( 'settings_page_googlereadersettings', __(
			'<p>You must provide your Google Account information, ie, your Login(email) / Password.<br />you can acces these informations :</p>'.
			'<ul>'.
			'<li><strong>starred</strong> : Items starred in Google Reader</li>'.
			'<li><strong>shared</strong> : Items shared in Google Reader</li>'.
			'<li><strong>tag</strong> : items tagged with specific tag</li>'.
			'<li><strong>read</strong> : Items read in Google Reader</li>'.
			'<li><strong>reading-list</strong> : Items in the reading list</li>'.
			'</ul><p>just try ...</p>'.
			'<p>Getting Google Reader items may be quite long, so use cache to save data and not reload items from google for every user</p>'.
			'<p>You must provide a full path to a writable directory</p>'
		,'google-reader'));
	}
	
	add_action('plugins_loaded','googlereader_activation');

	function googlereader_activation() {
	    global $pagenow;
		if ( $pagenow != 'plugins.php' ){ return; }
	
		// Set your requirements
		$required_plugin = 'wp-zff-zend-framework-full/wp_zff_zend_framework_full.php';
		if ( isset($_GET['activate']) && $_GET['activate'] == 'true' ) {
			if ( $plugins = get_option('active_plugins') ){
				if ( !in_array( $required_plugin , $plugins ) ){
					if ($keys = array_keys($plugins,"google-reader/google-reader.php") ) {
						unset($plugins[$keys[0]]);
						if ( update_option('active_plugins',$plugins) ){
							unset($_GET['activate']);
							add_action('admin_notices', '_phpversion_google_reader_core_library');
						}
					}			
				}
			}
		}
		
		if( isset($_GET['deactivate']) && $_GET['deactivate'] == 'true' )
		{
			if ( $plugins = get_option('active_plugins') ){
				if ( !in_array( $required_plugin , $plugins ) ){
					if ($keys = array_keys($plugins,"google-reader/google-reader.php") ) {
						unset($plugins[$keys[0]]);
						if ( update_option('active_plugins',$plugins) ){
							unset($_GET['deactivate']);
						}
					}			
				}
			}
		}
		// need also check if ZF is deactivated !
		
	}
	
	
	
}